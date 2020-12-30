<?php

declare(strict_types=1);

namespace Team\Infrastructure;

use Ramsey\Uuid\Uuid;
use Team\Domain\Member;
use Team\Domain\MemberCollection;
use Team\Domain\MemberId;
use Team\Domain\Team;
use Team\Domain\TeamId;
use Team\Domain\TeamRepository;

final class PdoTeamRepository implements TeamRepository
{
    private \PDO $db;

    public function __construct(string $dsn, string $username, string $password)
    {
        $this->db = new \PDO($dsn, $username, $password);
        $this->db->setAttribute(\PDO::ATTR_CASE, \PDO::CASE_NATURAL);
        $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    public function getAll(): \Generator
    {
        $statement = $this->db->query('SELECT uuid, name FROM team');
        if (!$statement) {
            return;
        }
        while ($row = $statement->fetchObject(ResultTeam::class)) {
            yield $this->toEntityTeam($row);
        }
        $statement->closeCursor();
    }

    private function toEntityTeam(ResultTeam $result): Team
    {
        $teamId = new TeamId(self::uuidToString($result->uuid));
        $members = new MemberCollection($this->getMembersByTeamId($teamId));

        return new Team($teamId, $result->name, $members);
    }

    private static function uuidToString(string $uuid): string
    {
        return Uuid::fromBytes($uuid)->toString();
    }

    /**
     * @return Member[]
     */
    private function getMembersByTeamId(TeamId $id): array
    {
        $members = [];
        $statement = $this->db->prepare(
            <<<'SQL'
SELECT member.uuid, member.firstname, member.lastname
FROM team
INNER JOIN member
    ON team.id = member.team_id
WHERE team.uuid = ?
SQL
        );
        $statement->execute([self::uuidToBinary($id)]);
        while ($row = $statement->fetchObject(ResultMember::class)) {
            $members[] = $this->toEntityMember($row);
        }
        $statement->closeCursor();

        return $members;
    }

    private static function uuidToBinary(\Stringable $uuid): string
    {
        return Uuid::fromString((string) $uuid)->getBytes();
    }

    private function toEntityMember(ResultMember $result): Member
    {
        $memberId = new MemberId(self::uuidToString($result->uuid));

        return new Member($memberId, $result->firstname, $result->lastname);
    }

    public function getById(TeamId $id): ?Team
    {
        $statement = $this->db->prepare('SELECT uuid, name FROM team WHERE uuid = ?');
        $statement->execute([self::uuidToBinary($id)]);
        $row = $statement->fetchObject(ResultTeam::class);
        $statement->closeCursor();

        return $row ? $this->toEntityTeam($row) : null;
    }

    public function loadFixtures(): void
    {
        $this->initDatabase();
    }

    private function initDatabase(): void
    {
        $statement = $this->db->query('SELECT DATABASE( )');
        if (!$statement) {
            throw new \RuntimeException('Unable to find database.');
        }
        $database = $statement->fetchColumn();

        $this->db->exec(sprintf('DROP DATABASE IF EXISTS `%s`', $database));
        $this->db->exec(sprintf('CREATE DATABASE `%s` DEFAULT CHARACTER SET utf8mb4', $database));

        $this->db->exec(sprintf('USE `%s`', $database));
        $this->db->exec(
            <<<'SQL'
CREATE TABLE team ( id INTEGER UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    uuid BINARY( 16 ) UNIQUE NOT NULL,
                    name VARCHAR( 50 ) NOT NULL )
SQL
        );
        $this->db->exec(
            <<<'SQL'
CREATE TABLE member ( id INTEGER UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                      uuid BINARY( 16 ) UNIQUE NOT NULL,
                      team_id INTEGER UNSIGNED NOT NULL,
                      firstname VARCHAR( 50 ) NOT NULL,
                      lastname VARCHAR( 50 ) DEFAULT NULL )
SQL
        );
        $this->db->exec(
            <<<'SQL'
ALTER TABLE member
    ADD FOREIGN KEY ( team_id ) REFERENCES team ( id ) ON DELETE CASCADE ON UPDATE CASCADE
SQL
        );

        $members = new MemberCollection();
        $members[] = new Member(new MemberId(Uuid::uuid4()->toString()), 'Buffy', 'Summers');
        $members[] = new Member(new MemberId(Uuid::uuid4()->toString()), 'Xander', 'Harris');
        $members[] = new Member(new MemberId(Uuid::uuid4()->toString()), 'Willow', 'Rosenberg');
        $members[] = new Member(new MemberId(Uuid::uuid4()->toString()), 'Cordelia', 'Chase');
        $members[] = new Member(new MemberId(Uuid::uuid4()->toString()), 'Rupert', 'Giles');
        $members[] = new Member(new MemberId(Uuid::uuid4()->toString()), 'Angel');
        $members[] = new Member(new MemberId(Uuid::uuid4()->toString()), 'Daniel', 'Osbourne');
        $members[] = new Member(new MemberId(Uuid::uuid4()->toString()), 'Spike');
        $members[] = new Member(new MemberId(Uuid::uuid4()->toString()), 'Riley', 'Finn');
        $members[] = new Member(new MemberId(Uuid::uuid4()->toString()), 'Anya', 'Jenkins');
        $teamId = new TeamId(Uuid::uuid4()->toString());
        $this->save(new Team($teamId, 'Buffy the Vampire Slayer', $members));
        printf("Team added: %s\n", (string) $teamId);

        $members = new MemberCollection();
        $members[] = new Member(new MemberId(Uuid::uuid4()->toString()), 'Eddard', 'Stark');
        $members[] = new Member(new MemberId(Uuid::uuid4()->toString()), 'Robert', 'Baratheon');
        $members[] = new Member(new MemberId(Uuid::uuid4()->toString()), 'Jaime', 'Lannister');
        $members[] = new Member(new MemberId(Uuid::uuid4()->toString()), 'Catelyn', 'Stark');
        $members[] = new Member(new MemberId(Uuid::uuid4()->toString()), 'Cersei', 'Lannister');
        $members[] = new Member(new MemberId(Uuid::uuid4()->toString()), 'Daenerys', 'Targaryen');
        $members[] = new Member(new MemberId(Uuid::uuid4()->toString()), 'Jorah', 'Mormont');
        $members[] = new Member(new MemberId(Uuid::uuid4()->toString()), 'Viserys', 'Targaryen');
        $members[] = new Member(new MemberId(Uuid::uuid4()->toString()), 'John', 'Snow');
        $members[] = new Member(new MemberId(Uuid::uuid4()->toString()), 'Robb', 'Stark');
        $teamId = new TeamId(Uuid::uuid4()->toString());
        $this->save(new Team($teamId, 'Game of Thrones', $members));
        printf("Team added: %s\n", (string) $teamId);
    }

    public function save(Team $team): void
    {
        try {
            $this->db->beginTransaction();

            $statement = $this->db->prepare('SELECT id FROM team WHERE uuid = ?');
            $statement->execute([self::uuidToBinary($team->getId())]);
            $teamId = (int) $statement->fetchColumn();
            $statement->closeCursor();

            if ($teamId > 0) {
                $statement = $this->db->prepare('UPDATE team SET name = ? WHERE id = ?');
                $statement->execute([$team->getName(), $teamId]);
                $statement->closeCursor();
            } else {
                $statement = $this->db->prepare('INSERT INTO team ( uuid, name ) VALUES ( ?, ? )');
                $statement->execute([self::uuidToBinary($team->getId()), $team->getName()]);
                $statement->closeCursor();
                $teamId = (int) $this->db->lastInsertId('team.id');
            }

            $statement = $this->db->prepare(
                <<<'SQL'
INSERT INTO member ( uuid, team_id, firstname, lastname )
VALUES ( ?, ?, ?, ? )
SQL
            );
            /** @var Member $member */
            foreach ($team->getMembers() as $member) {
                $statement->execute(
                    [
                        self::uuidToBinary($member->getId()),
                        $teamId,
                        $member->getFirstname(),
                        $member->getLastname(),
                    ]
                );
            }
            $statement->closeCursor();

            $this->db->commit();
        } catch (\PDOException $exception) {
            $this->db->rollBack();
            // TODO: throw exception
        }
    }
}

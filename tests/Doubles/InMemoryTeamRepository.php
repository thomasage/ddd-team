<?php

declare(strict_types=1);

namespace Test\Doubles;

use Team\Domain\Member;
use Team\Domain\MemberCollection;
use Team\Domain\Team;
use Team\Domain\TeamId;
use Team\Domain\TeamRepository;

final class InMemoryTeamRepository implements TeamRepository
{
    /**
     * @var Team[]
     */
    private array $teams;

    public function __construct()
    {
        $memberIdProvider = new Md5MemberIdProvider();

        $members = new MemberCollection();
        $members[] = new Member($memberIdProvider->generate(), 'Buffy', 'Summers');
        $members[] = new Member($memberIdProvider->generate(), 'Xander', 'Harris');
        $members[] = new Member($memberIdProvider->generate(), 'Willow', 'Rosenberg');
        $members[] = new Member($memberIdProvider->generate(), 'Cordelia', 'Chase');
        $members[] = new Member($memberIdProvider->generate(), 'Rupert', 'Giles');
        $members[] = new Member($memberIdProvider->generate(), 'Angel');
        $members[] = new Member($memberIdProvider->generate(), 'Daniel', 'Osbourne');
        $members[] = new Member($memberIdProvider->generate(), 'Spike');
        $members[] = new Member($memberIdProvider->generate(), 'Riley', 'Finn');
        $members[] = new Member($memberIdProvider->generate(), 'Anya', 'Jenkins');
        $this->save(new Team(new TeamId('BUFFY'), 'Buffy the Vampire Slayer', $members));

        $members = new MemberCollection();
        $members[] = new Member($memberIdProvider->generate(), 'Eddard', 'Stark');
        $members[] = new Member($memberIdProvider->generate(), 'Robert', 'Baratheon');
        $members[] = new Member($memberIdProvider->generate(), 'Jaime', 'Lannister');
        $members[] = new Member($memberIdProvider->generate(), 'Catelyn', 'Stark');
        $members[] = new Member($memberIdProvider->generate(), 'Cersei', 'Lannister');
        $members[] = new Member($memberIdProvider->generate(), 'Daenerys', 'Targaryen');
        $members[] = new Member($memberIdProvider->generate(), 'Jorah', 'Mormont');
        $members[] = new Member($memberIdProvider->generate(), 'Viserys', 'Targaryen');
        $members[] = new Member($memberIdProvider->generate(), 'John', 'Snow');
        $members[] = new Member($memberIdProvider->generate(), 'Robb', 'Stark');
        $this->save(new Team(new TeamId('GOT'), 'Game of Thrones', $members));
    }

    public function save(Team $team): void
    {
        $this->teams[(string) $team->getId()] = $team;
    }

    /**
     * @return \Generator<Team>
     */
    public function getAll(): \Generator
    {
        foreach ($this->teams as $team) {
            yield $team;
        }
    }

    public function getById(TeamId $id): ?Team
    {
        return $this->teams[(string) $id] ?? null;
    }
}

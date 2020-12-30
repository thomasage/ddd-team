<?php

declare(strict_types=1);

namespace Test\Infrastructure;

use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Team\Domain\Member;
use Team\Domain\MemberCollection;
use Team\Domain\MemberId;
use Team\Domain\Team;
use Team\Domain\TeamId;
use Team\Infrastructure\PdoTeamRepository;

final class PdoTeamRepositoryTest extends TestCase
{
    public function testATeamCanBeSaved(): void
    {
        // Given
        $members = new MemberCollection();
        $members[] = new Member(new MemberId(Uuid::uuid4()->toString()), 'Prue', 'Halliwell');
        $members[] = new Member(new MemberId(Uuid::uuid4()->toString()), 'Piper', 'Halliwell');
        $members[] = new Member(new MemberId(Uuid::uuid4()->toString()), 'Phoebe', 'Halliwell');
        $teamId = new TeamId(Uuid::uuid4()->toString());
        $team = new Team($teamId, 'Charmed', $members);
        $dsn = sprintf('%s:dbname=%s;host=%s;port=%d', 'mysql', 'main', '127.0.0.1', 49153);
        $repository = new PdoTeamRepository($dsn, 'root', 'password');

        // When
        $repository->save($team);

        // Then
        $foundTeam = $repository->getById($teamId);
        self::assertInstanceOf(Team::class, $foundTeam);
        self::assertSame((string) $team->getId(), $foundTeam ? (string) $foundTeam->getId() : null);
        self::assertSame($team->getName(), $foundTeam ? $foundTeam->getName() : null);
        self::assertSame(count($members), $foundTeam ? $foundTeam->getTotalMembers() : null);
    }
}

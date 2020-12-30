<?php

declare(strict_types=1);

namespace Test\Application\GetTeam;

use PHPUnit\Framework\TestCase;
use Team\Application\GetTeam\GetTeamHandler;
use Team\Application\GetTeam\GetTeamPresenter;
use Team\Application\GetTeam\GetTeamRequest;
use Team\Application\GetTeam\GetTeamResponse;
use Team\Application\GetTeam\TeamResult;
use Test\Doubles\InMemoryTeamRepository;

final class GetTeamHandlerTest extends TestCase implements GetTeamPresenter
{
    private GetTeamResponse $response;

    public function testAExistingTeamCanBeFound(): void
    {
        // Given
        $repository = new InMemoryTeamRepository();
        $handler = new GetTeamHandler($repository);
        $request = new GetTeamRequest();
        $request->id = 'BUFFY';

        // When
        $handler->handle($request, $this);

        // Then
        self::assertInstanceOf(TeamResult::class, $this->response->team);
        self::assertSame('Buffy the Vampire Slayer', $this->response->team->name ?? null);
    }

    public function testANonexistentTeamCannotBeFound(): void
    {
        // Given
        $repository = new InMemoryTeamRepository();
        $handler = new GetTeamHandler($repository);
        $request = new GetTeamRequest();
        $request->id = 'FRIENDS';

        // When
        $handler->handle($request, $this);

        // Then
        self::assertNull($this->response->team);
    }

    public function present(GetTeamResponse $response): void
    {
        $this->response = $response;
    }
}

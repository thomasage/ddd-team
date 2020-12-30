<?php

declare(strict_types=1);

namespace Test\Application\AddTeam;

use PHPUnit\Framework\TestCase;
use Team\Application\AddTeam\AddTeamHandler;
use Team\Application\AddTeam\AddTeamPresenter;
use Team\Application\AddTeam\AddTeamRequest;
use Team\Application\AddTeam\AddTeamResponse;
use Test\Doubles\InMemoryTeamRepository;
use Test\Doubles\Md5MemberIdProvider;
use Test\Doubles\Md5TeamIdProvider;

final class AddTeamHandlerTest extends TestCase implements AddTeamPresenter
{
    private AddTeamResponse $response;

    public function testATeamCanBeAdded(): void
    {
        // Given
        $memberIdProvider = new Md5MemberIdProvider();
        $teamIdProvider = new Md5TeamIdProvider();
        $repository = new InMemoryTeamRepository();
        $handler = new AddTeamHandler($teamIdProvider, $repository, $memberIdProvider);
        $request = new AddTeamRequest();
        $request->members[] = ['firstname' => 'Prue', 'lastname' => 'Halliwell'];
        $request->members[] = ['firstname' => 'Piper', 'lastname' => 'Halliwell'];
        $request->members[] = ['firstname' => 'Phoebe', 'lastname' => 'Halliwell'];
        $request->name = 'Charmed';

        // When
        $handler->handle($request, $this);

        // Then
        self::assertTrue($this->response->added);
    }

    public function present(AddTeamResponse $response): void
    {
        $this->response = $response;
    }
}

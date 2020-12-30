<?php

declare(strict_types=1);

namespace Team\Application\AddTeam;

use Team\Domain\Member;
use Team\Domain\MemberCollection;
use Team\Domain\MemberIdProvider;
use Team\Domain\Team;
use Team\Domain\TeamIdProvider;
use Team\Domain\TeamRepository;

final class AddTeamHandler implements AddTeam
{
    private MemberIdProvider $memberIdProvider;
    private TeamIdProvider $teamIdProvider;
    private TeamRepository $teamRepository;

    public function __construct(
        TeamIdProvider $teamIdProvider,
        TeamRepository $teamRepository,
        MemberIdProvider $memberIdProvider
    ) {
        $this->memberIdProvider = $memberIdProvider;
        $this->teamIdProvider = $teamIdProvider;
        $this->teamRepository = $teamRepository;
    }

    public function handle(AddTeamRequest $request, AddTeamPresenter $presenter): void
    {
        $members = new MemberCollection();
        foreach ($request->members as $member) {
            $members[] = new Member($this->memberIdProvider->generate(), $member['firstname'], $member['lastname']);
        }
        $team = new Team($this->teamIdProvider->generate(), $request->name, $members);
        $this->teamRepository->save($team);
        $response = new AddTeamResponse();
        $response->added = true;
        $presenter->present($response);
    }
}

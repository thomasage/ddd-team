<?php

declare(strict_types=1);

namespace Team\Application\GetTeam;

use Team\Domain\Member;
use Team\Domain\Team;
use Team\Domain\TeamId;
use Team\Domain\TeamRepository;

final class GetTeamHandler implements GetTeam
{
    private TeamRepository $teamRepository;

    public function __construct(TeamRepository $teamRepository)
    {
        $this->teamRepository = $teamRepository;
    }

    /**
     * @return array{firstname:string,lastname:string}
     */
    private static function toDtoMember(Member $member): array
    {
        return [
            'firstname' => $member->getFirstname(),
            'lastname' => (string) $member->getLastname(),
        ];
    }

    public function handle(GetTeamRequest $request, GetTeamPresenter $presenter): void
    {
        $team = $this->teamRepository->getById(new TeamId($request->id));
        $response = new GetTeamResponse();
        $response->team = $team ? self::toDtoTeam($team) : null;
        $presenter->present($response);
    }

    private static function toDtoTeam(Team $team): TeamResult
    {
        $dto = new TeamResult();
        $dto->members = array_map([__CLASS__, 'toDtoMember'], iterator_to_array($team->getMembers()));
        $dto->name = $team->getName();

        return $dto;
    }
}

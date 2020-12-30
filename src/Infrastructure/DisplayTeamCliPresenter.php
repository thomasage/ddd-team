<?php

declare(strict_types=1);

namespace Team\Infrastructure;

use Team\Application\GetTeam\GetTeamPresenter;
use Team\Application\GetTeam\GetTeamResponse;

final class DisplayTeamCliPresenter implements GetTeamPresenter
{
    private DisplayTeamCliViewModel $viewModel;

    public function __construct()
    {
        $this->viewModel = new DisplayTeamCliViewModel();
    }

    /**
     * @param array{firstname:string,lastname:string} $member
     */
    private static function toDtoMember(array $member): string
    {
        return sprintf('%s %s', $member['firstname'], strtoupper($member['lastname']));
    }

    public function present(GetTeamResponse $response): void
    {
        if ($response->team) {
            $this->viewModel->found = true;
            $this->viewModel->members = array_map([__CLASS__, 'toDtoMember'], $response->team->members);
            $this->viewModel->teamName = $response->team->name;
        } else {
            $this->viewModel->found = false;
            $this->viewModel->errors[] = 'This ID does not exist.';
        }
    }

    public function getViewModel(): DisplayTeamCliViewModel
    {
        return $this->viewModel;
    }
}

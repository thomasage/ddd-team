<?php

namespace Team\Application\GetTeam;

interface GetTeamPresenter
{
    public function present(GetTeamResponse $response): void;
}

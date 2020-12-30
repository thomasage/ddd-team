<?php

namespace Team\Application\GetTeam;

interface GetTeam
{
    public function handle(GetTeamRequest $request, GetTeamPresenter $presenter): void;
}

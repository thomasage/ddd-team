<?php

namespace Team\Application\AddTeam;

interface AddTeam
{
    public function handle(AddTeamRequest $request, AddTeamPresenter $presenter): void;
}

<?php

declare(strict_types=1);

namespace Team\Infrastructure;

use Team\Application\GetTeam\GetTeam;
use Team\Application\GetTeam\GetTeamRequest;

final class DisplayTeamCommand
{
    private GetTeam $handler;
    private DisplayTeamCliPresenter $presenter;
    private DisplayTeamCliView $view;

    public function __construct(GetTeam $handler, DisplayTeamCliPresenter $presenter, DisplayTeamCliView $view)
    {
        $this->handler = $handler;
        $this->presenter = $presenter;
        $this->view = $view;
    }

    public function __invoke(string $teamId): int
    {
        $request = new GetTeamRequest();
        $request->id = $teamId;
        $this->handler->handle($request, $this->presenter);

        return $this->view->render($this->presenter->getViewModel());
    }
}

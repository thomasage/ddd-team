<?php

declare(strict_types=1);

use Team\Application\GetTeam\GetTeamHandler;
use Team\Infrastructure\DisplayTeamCliPresenter;
use Team\Infrastructure\DisplayTeamCliView;
use Team\Infrastructure\DisplayTeamCommand;
use Team\Infrastructure\PdoTeamRepository;

require_once __DIR__.'/../vendor/autoload.php';

$teamId = $_SERVER['argv'][1] ?? null;
if (!$teamId) {
    exit(1);
}

$dsn = sprintf('%s:dbname=%s;host=%s;port=%d', 'mysql', 'main', '127.0.0.1', 49153);
$teamRepository = new PdoTeamRepository($dsn, 'root', 'password');
$handler = new GetTeamHandler($teamRepository);
$presenter = new DisplayTeamCliPresenter();
$view = new DisplayTeamCliView();
exit((new DisplayTeamCommand($handler, $presenter, $view))($teamId));

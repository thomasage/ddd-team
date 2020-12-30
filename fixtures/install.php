<?php

declare(strict_types=1);

use Team\Infrastructure\PdoTeamRepository;

require_once __DIR__.'/../vendor/autoload.php';

$dsn = sprintf('%s:dbname=%s;host=%s;port=%d', 'mysql', 'main', '127.0.0.1', 49153);
$repository = new PdoTeamRepository($dsn, 'root', 'password');
$repository->loadFixtures();

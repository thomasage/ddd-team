<?php

declare(strict_types=1);

namespace Team\Application\GetTeam;

final class TeamResult
{
    /**
     * @var array<array{firstname:string,lastname:string}>
     */
    public array $members = [];
    public string $name;
}

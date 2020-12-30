<?php

declare(strict_types=1);

namespace Team\Application\AddTeam;

final class AddTeamRequest
{
    /**
     * @var array<int,array{firstname:string,lastname:string|null}>
     */
    public array $members = [];
    public string $name;
}

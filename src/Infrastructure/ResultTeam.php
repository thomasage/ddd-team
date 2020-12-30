<?php

declare(strict_types=1);

namespace Team\Infrastructure;

final class ResultTeam
{
    /**
     * @var ResultMember[]
     */
    public array $members = [];
    public string $name;
    public string $uuid;
}

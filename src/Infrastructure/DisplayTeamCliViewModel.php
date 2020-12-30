<?php

declare(strict_types=1);

namespace Team\Infrastructure;

final class DisplayTeamCliViewModel
{
    /**
     * @var string[]
     */
    public array $errors = [];
    public bool $found;
    /**
     * @var string[]
     */
    public array $members = [];
    public ?string $teamName;
}

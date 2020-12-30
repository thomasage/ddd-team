<?php

declare(strict_types=1);

namespace Team\Domain;

final class TeamId implements \Stringable
{
    private string $uuid;

    public function __construct(string $uuid)
    {
        $this->uuid = $uuid;
    }

    public function __toString(): string
    {
        return $this->uuid;
    }
}

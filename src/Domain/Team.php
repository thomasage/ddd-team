<?php

declare(strict_types=1);

namespace Team\Domain;

final class Team
{
    private TeamId $id;
    private string $name;
    private MemberCollection $members;

    public function __construct(TeamId $id, string $name, MemberCollection $members)
    {
        $this->id = $id;
        $this->name = $name;
        $this->members = $members;
    }

    public function getId(): TeamId
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getTotalMembers(): int
    {
        return count($this->members);
    }

    public function getMembers(): MemberCollection
    {
        return $this->members;
    }
}

<?php

declare(strict_types=1);

namespace Team\Domain;

final class Member
{
    private MemberId $id;
    private string $firstname;
    private ?string $lastname;

    public function __construct(MemberId $id, string $firstname, ?string $lastname = null)
    {
        $this->id = $id;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
    }

    public function getId(): MemberId
    {
        return $this->id;
    }

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }
}

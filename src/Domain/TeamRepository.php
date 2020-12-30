<?php

namespace Team\Domain;

interface TeamRepository
{
    /**
     * @return \Generator<Team>
     */
    public function getAll(): \Generator;

    public function getById(TeamId $id): ?Team;

    public function save(Team $team): void;
}

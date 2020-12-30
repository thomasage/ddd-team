<?php

namespace Team\Domain;

interface TeamIdProvider
{
    public function generate(): TeamId;
}

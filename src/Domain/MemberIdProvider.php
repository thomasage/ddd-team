<?php

namespace Team\Domain;

interface MemberIdProvider
{
    public function generate(): MemberId;
}

<?php

declare(strict_types=1);

namespace Test\Doubles;

use Team\Domain\TeamId;
use Team\Domain\TeamIdProvider;

final class Md5TeamIdProvider implements TeamIdProvider
{
    public function generate(): TeamId
    {
        return new TeamId(md5(uniqid((string) mt_rand(), true)));
    }
}

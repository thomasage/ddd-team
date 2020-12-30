<?php

declare(strict_types=1);

namespace Test\Doubles;

use Team\Domain\MemberId;
use Team\Domain\MemberIdProvider;

final class Md5MemberIdProvider implements MemberIdProvider
{
    public function generate(): MemberId
    {
        return new MemberId(md5(uniqid((string) mt_rand(), true)));
    }
}

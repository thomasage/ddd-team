<?php

declare(strict_types=1);

namespace Team\Domain;

/**
 * @extends \ArrayIterator<int,Member>
 */
final class MemberCollection extends \ArrayIterator
{
    /**
     * @param int|null $key
     * @param Member   $value
     */
    public function offsetSet($key, $value)
    {
        if (!$value instanceof Member) {
            throw new \InvalidArgumentException(sprintf('Value is not an instance of %s.', Member::class));
        }
        parent::offsetSet($key, $value);
    }

    /**
     * @param int $key
     */
    public function offsetGet($key): Member
    {
        return parent::offsetGet($key);
    }
}

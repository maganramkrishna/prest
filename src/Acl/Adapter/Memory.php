<?php

namespace Prest\Acl\Adapter;

use Prest\Acl\MountAwareTrait;
use Phalcon\Acl\Adapter\Memory as PhMemory;
use Prest\Acl\MountingEnabledAdapterInterface;

/**
 * Prest\Acl\Adapter\Memory
 *
 * @package Prest\Acl\Adapter
 */
class Memory extends PhMemory implements MountingEnabledAdapterInterface
{
    use MountAwareTrait;
}

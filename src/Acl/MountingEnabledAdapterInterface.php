<?php

namespace Prest\Acl;

use Phalcon\Acl\AdapterInterface;

/**
 * Prest\Acl\MountingEnabledAdapterInterface
 *
 * @package Prest\Acl
 */
interface MountingEnabledAdapterInterface extends AdapterInterface
{
    /**
     * Mounts the mountable object onto the AC and setting up the right access.
     *
     * @param MountableInterface $mountable
     * @return $this
     */
    public function mount(MountableInterface $mountable);

    /**
     * Mounts an array of mountable objects onto the ACL and setting up the right access.
     *
     * @param MountableInterface[] $mountables
     * @return $this
     */
    public function mountMany(array $mountables);
}

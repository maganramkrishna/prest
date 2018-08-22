<?php

namespace Prest\Acl;

use Phalcon\Acl;
use Phalcon\Acl\AdapterInterface;

/**
 * Prest\Acl\MountAwareTrait
 *
 * @package Prest\Acl
 */
trait MountAwareTrait
{
    /**
     * {@inheritdoc}
     *
     * @param MountableInterface[] $mountables
     * @return $this
     */
    public function mountMany(array $mountables)
    {
        foreach ($mountables as $mountable) {
            $this->mount($mountable);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @param MountableInterface $mountable
     * @return $this
     */
    public function mount(MountableInterface $mountable)
    {
        if (!$this instanceof AdapterInterface) {
            return $this;
        }

        $resources = $mountable->getAclResources();

        // Mount resources
        foreach ($resources as $resourceConfig) {
            if (count($resourceConfig) == 0) {
                continue;
            }

            $this->addResource($resourceConfig[0], $resourceConfig[1] ?? null);
        }

        $rules = $mountable->getAclRules($this->getRoles());

        array_map(function (int $access) use ($rules) {
            if (empty($rules[$access])) {
                return;
            }

            $rights = array_filter($rules[$access], function ($rule) {
                return count($rule) >= 2;
            });

            $method = $access == Acl::DENY ? 'deny' : 'allow';
            foreach ($rights as $right) {
                $this->{$method}($right[0], $right[1], $right[2] ?? null);
            }
        }, [Acl::DENY, Acl::ALLOW]);

        return $this;
    }
}

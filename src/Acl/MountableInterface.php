<?php

namespace Prest\Acl;

/**
 * Prest\Acl\MountableInterface
 *
 * @package Prest\Acl
 */
interface MountableInterface
{
    /**
     * Returns the ACL resources in the following format:
     *
     * <code>
     * [
     *   [ Resources, ['endpoint1', 'endpoint2'] ]
     * ]
     * </code>
     *
     * @return array
     */
    public function getAclResources(): array;

    /**
     * Returns the ACL rules in the following format:
     *
     * <code>
     * [
     *   Acl::ALLOW => [['rolename', 'resourcename', 'endpointname], ['rolename', 'resourcename', 'endpointname]],
     *   Acl::DENY => [['rolename', 'resourcename', 'endpointname], ['rolename', 'resourcename', 'endpointname]]
     * ]
     * </code>
     *
     * @param array $roles The currently registered role on the ACL
     * @return array
     */
    public function getAclRules(array $roles): array;
}

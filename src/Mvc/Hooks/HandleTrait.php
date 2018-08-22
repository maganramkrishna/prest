<?php

namespace Prest\Mvc\Hooks;

use Phalcon\Mvc\Model;

/**
 * Prest\Mvc\Hooks\HandleTrait
 *
 * @package Prest\Mvc\Hooks
 */
trait HandleTrait
{
    protected function beforeHandle()
    {
    }

    protected function beforeHandleCreate()
    {
    }

    protected function beforeHandleWrite()
    {
    }

    protected function beforeHandleRead()
    {
    }

    protected function beforeHandleFind($identity)
    {
    }

    protected function beforeHandleUpdate($identity)
    {
    }

    protected function beforeHandleAll()
    {
    }

    protected function afterHandle()
    {
    }

    protected function afterHandleCreate(Model $createdItem, $data, $response)
    {
    }

    protected function afterHandleWrite()
    {
    }

    protected function afterHandleRead()
    {
    }

    protected function afterHandleFind($item, $response)
    {
    }

    protected function afterHandleAll($data, $response)
    {
    }

    protected function afterHandleUpdate(Model $updatedItem, $data, $response)
    {
    }

    protected function beforeHandleRemove($identity)
    {
    }

    protected function afterHandleRemove(Model $removedItem, $response)
    {
    }
}

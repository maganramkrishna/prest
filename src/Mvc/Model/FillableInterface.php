<?php

namespace Prest\Mvc\Model;

/**
 * Prest\Mvc\Model\FillableInterface
 *
 * @package Prest\Mvc\Model
 */
interface FillableInterface
{
    /**
     * Returns attributes that are mass assignable.
     *
     * @return array
     */
    public function whitelist(): array;
}

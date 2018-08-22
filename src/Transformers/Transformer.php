<?php

namespace Prest\Transformers;

use Prest\Di\InjectableTrait;
use Phalcon\Di\InjectionAwareInterface;
use League\Fractal\TransformerAbstract;

/**
 * Prest\Transformers\Transformer
 *
 * @package Prest\Transformers
 */
class Transformer extends TransformerAbstract implements InjectionAwareInterface
{
    use InjectableTrait;

    public function int($value)
    {
        return $this->formatHelper->int($value);
    }

    public function float($value)
    {
        return $this->formatHelper->float($value);
    }

    public function double($value)
    {
        return $this->formatHelper->float($value);
    }

    public function bool($value)
    {
        return $this->formatHelper->bool($value);
    }

    public function date($value)
    {
        return $this->formatHelper->date($value);
    }
}

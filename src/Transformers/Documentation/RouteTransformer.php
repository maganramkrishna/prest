<?php

namespace Prest\Transformers\Documentation;

use Phalcon\Mvc\Router\Route;
use Prest\Transformers\Transformer;

class RouteTransformer extends Transformer
{
    public function transform(Route $route)
    {
        return [
            'name' => $route->getName(),
            'pattern' => $route->getPattern()
        ];
    }
}

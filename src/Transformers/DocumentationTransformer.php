<?php

namespace Prest\Transformers;

use Prest\Export\Documentation;
use Prest\Transformers\Documentation\CollectionTransformer;
use Prest\Transformers\Documentation\RouteTransformer;

class DocumentationTransformer extends Transformer
{
    public $defaultIncludes = [
        'routes',
        'collections'
    ];

    public function transform(Documentation $documentation)
    {
        return [
            'name' => $documentation->name,
            'basePath' => $documentation->basePath
        ];
    }

    public function includeRoutes(Documentation $documentation)
    {
        return $this->collection($documentation->getRoutes(), new RouteTransformer);
    }

    public function includeCollections(Documentation $documentation)
    {
        return $this->collection($documentation->getCollections(), new CollectionTransformer);
    }
}

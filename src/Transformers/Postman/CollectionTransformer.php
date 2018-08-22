<?php

namespace Prest\Transformers\Postman;

use Prest\Transformers\Transformer;
use Prest\Export\Postman\Collection;

class CollectionTransformer extends Transformer
{
    protected $defaultIncludes = [
        'requests',
    ];

    public function transform(Collection $collection)
    {
        return [
            'id' => $collection->id,
            'name' => $collection->name,
        ];
    }

    public function includeRequests(Collection $collection)
    {
        return $this->collection($collection->getRequests(), new RequestTransformer);
    }
}

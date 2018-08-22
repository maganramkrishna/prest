<?php

namespace Prest\Transformers\Documentation;

use Prest\Transformers\Transformer;
use Prest\Export\Documentation\Endpoint;

class EndpointTransformer extends Transformer
{
    public function transform(Endpoint $endpoint)
    {
        return [
            'name' => $endpoint->getName(),
            'description' => $endpoint->getDescription(),
            'httpMethod' => $endpoint->getHttpMethod(),
            'path' => $endpoint->getPath(),
            'exampleResponse' => $endpoint->getExampleResponse(),
            'allowedRoles' => $endpoint->getAllowedRoles()
        ];
    }
}

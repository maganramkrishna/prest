<?php

namespace Prest\Transformers\Postman;

use Prest\Export\Postman\Request;
use Prest\Transformers\Transformer;

class RequestTransformer extends Transformer
{
    public function transform(Request $request)
    {
        return [
            'collectionId' => $request->collectionId,
            'id' => $request->id,
            'name' => $request->name,
            'description' => $request->description,
            'url' => $request->url,
            'method' => $request->method,
            'headers' => $request->headers,
            'data' => $request->data,
            'dataMode' => $request->dataMode,
        ];
    }
}

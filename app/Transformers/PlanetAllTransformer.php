<?php

namespace App\Transformers;

class PlanetAllTransformer extends Transformer
{
    /**
     * {@inheritdoc}
     *
     * @param \App\Models\Planet $item
     */
    public function transform($item)
    {
        return [
            'id' => $item->id,
            'resource_id' => $item->resource_id,
            'name' => $item->display_name,
            'x' => $item->x,
            'y' => $item->y,
        ];
    }
}

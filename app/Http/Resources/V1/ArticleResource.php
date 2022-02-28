<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;
use App\JsonApi\Traits\JsonApiResource;

class ArticleResource extends JsonResource
{
    use JsonApiResource;

    public function toJsonApi(): array
    {
        return array(
            'title' => $this->resource->title,
            'slug' => $this->resource->slug,
            'content' => $this->resource->content
        );
    }
}

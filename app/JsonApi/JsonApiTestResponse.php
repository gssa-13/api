<?php

namespace App\JsonApi;

use Closure;
use Illuminate\Support\Str;
use Illuminate\Testing\TestResponse;
use PHPUnit\Framework\Assert as PHPUnit;
use PHPUnit\Framework\ExpectationFailedException;

class JsonApiTestResponse
{

    public function assertJsonApiValidationErrors(): Closure
    {
        return function ($attribute) {
            /** @var TestResponse $this  */
            $pointer = Str::of($attribute)->startsWith('data')
                ? "/".Str::replace('.', '/', $attribute)
                : "/data/attributes/{$attribute}";
            try {
                $this->assertJsonFragment([
                    'source' => ['pointer' => $pointer]
                ]);
            } catch (ExpectationFailedException $e) {
                PHPUnit::fail(
                    "Failed to find a JSON:API validation error for key: '{$attribute}'"
                    .PHP_EOL.PHP_EOL.
                    $e->getMessage()
                );
            }

            try {
                $this->assertJsonStructure([
                    'errors' => [
                        ['title', 'detail','source' => ['pointer']]
                    ]
                ]);
            } catch (ExpectationFailedException $e) {
                PHPUnit::fail(
                    "Failed to find a valid JSON:API error response"
                    .PHP_EOL.PHP_EOL.
                    $e->getMessage()
                );
            }

            $this->assertHeader(
                'content-type', 'application/vnd.api+json'
            )->assertStatus(422);
        };
    }

    public function assertJsonApiResource(): Closure
    {
        return function ($model, $attributes) {
            /** @var TestResponse $this */

            $this->assertJson([
                'data' => [
                    'type' => $model->getResourceType(),
                    'id' => (string) $model->getRouteKey(),
                    'attributes' => $attributes,
                    'links' => [
                        'self' => route('api.v1.'.$model->getResourceType().'.show', $model )
                    ]
                ]
            ])->assertHeader(
                'Location',
                route('api.v1.'.$model->getResourceType().'.show', $model )
            );
        };
    }

    public function assertJsonApiResourceCollection(): Closure
    {
        return function ($collection, $attributesKeys) {
            /** @var TestResponse $this */

            try {
                $this->assertJsonStructure([
                    'data' => [
                        '*' => [
                            'attributes' => $attributesKeys
                        ]
                    ]
                ]);
            } catch (ExpectationFailedException $e) {
                PHPUnit::fail(
                    "Failed to find a key inside the attributes key"
                    .PHP_EOL.PHP_EOL.
                    $e->getMessage()
                );
            }



            foreach ($collection as $model)
            {
                $this->assertJsonFragment([
                    'type' => 'articles',
                    'id' => (string) $model->getRouteKey(),
                    'links' => [
                        'self' => route('api.v1.'.$model->getResourceType().'.show', $model )
                    ]
                ]);
            }
        };
    }
}

<?php

namespace Tests\Unit\V1\JsonApi;

use PHPUnit\Framework\TestCase;
use App\JsonApi\Document;

class DocumentTest extends TestCase
{

    /** @test */
    public function can_create_json_api_documents()
    {
        $document =  Document::type('articles')->id('article-id')
            ->attributes([
                'title' => 'Article title'
            ])->toArray();

        $expected = array(
            'data' => array(
                'type' => 'articles',
                'id' => 'article-id',
                'attributes' => array(
                    'title' => 'Article title'
                )
            )
        );

        $this->assertEquals($expected, $document);
    }
}

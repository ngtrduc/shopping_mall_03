<?php

namespace Elasticsearch\Service;

class ElasticSearchManager
{
    private $client;

    public function __construct($client)
    {
        $this->client = $client;
    }

    public function getClient()
    {
        return $this->client;
    }

    public function initData()
    {
        $params = [
            'index' => 'product',
        ];

        $response = $this->client->indices()->create($params);

        $params = [
            'index' => 'category',
        ];

        $response = $this->client->indices()->create($params);

        $params = [
            'index' => 'sale',
        ];

        $response = $this->client->indices()->create($params);

        $params = [
            'index' => 'tag',
        ];

        $response = $this->client->indices()->create($params);
    }

    public function indexProduct($id, $product)
    {
        $params = [
            'index' => 'product',
            'type' => 'product',
            'id' => $id,
            'body' => $product
        ];

        $this->client->index($params);
    }

    public function updateProduct($product)
    {

    }

    public function deleteProduct()
    {
        for ($id = 1; $id < 4; $id++) {
            $params = [
                'index' => 'product',
                'type' => 'product',
                'id' => $id,
            ];

            $this->client->delete($params);
        }
    }
}
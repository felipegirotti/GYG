<?php

namespace GYG\Infrastructure\Client;

use GYG\Infrastructure\Client\Entities\SearchProductResponse;
use GuzzleHttp\Client;

class PartnerClient
{

    const ERROR_TO_FETCH_DATA = 'Error to fetch data';

    /**
     * @var \GuzzleHttp\Client
     */
    private $httpClient;

    /**
     * PartnerClient constructor.
     * @param Client $httpClient
     */
    public function __construct(Client $httpClient)
    {
        $this->httpClient = $httpClient;
    }


    /**
     * @return \ArrayIterator
     */
    public function search()
    {

        $clientResponse = $this->httpClient->get('');
        if ($clientResponse->getStatusCode() !== 200) {
            throw new \RuntimeException(self::ERROR_TO_FETCH_DATA);// @codeCoverageIgnore
        }

        $products = json_decode($clientResponse->getBody()->getContents(), true);

        $this->validate($products);

        $items = $this->populateItems($products);

        return new \ArrayIterator($items);
    }

    private function validate($products)
    {
        if (!is_array($products) || !isset($products['product_availabilities'])) {
            throw new \RuntimeException(self::ERROR_TO_FETCH_DATA);
        }
    }

    private function validateItem($product)
    {
        return isset($product['activity_start_datetime'])
            && isset($product['places_available'])
            && isset($product['activity_duration_in_minutes'])
            && isset($product['product_id']);
    }

    /**
     * @param $products
     * @return array
     */
    protected function populateItems($products)
    {
        $items = [];
        foreach ($products['product_availabilities'] as $product) {
            if ($this->validateItem($product)) {
                $items[] = new SearchProductResponse(
                    $product['activity_start_datetime'],
                    $product['places_available'],
                    $product['activity_duration_in_minutes'],
                    $product['product_id']
                );
            }
        }
        return $items;
    }
}

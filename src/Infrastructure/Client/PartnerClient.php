<?php

namespace GYG\Infrastructure\Client;

use GYG\Infrastructure\Client\Entities\SearchProductResponse;
use GuzzleHttp\Client;
use GYG\Infrastructure\Client\Entities\SearchProductsRequest;

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
     * @param SearchProductsRequest $request
     * @return \ArrayIterator
     */
    public function search(SearchProductsRequest $request)
    {

        $clientResponse = $this->httpClient->get('');
        if ($clientResponse->getStatusCode() !== 200) {
            throw new \RuntimeException(self::ERROR_TO_FETCH_DATA); // @codeCoverageIgnore
        }

        $response = json_decode($clientResponse->getBody()->getContents(), true);

        $this->validate($response);

        $items = $this->populateItems(new BetweenPeriodIterator(
            new \ArrayIterator($response['product_availabilities']),
            $request
        ));

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
        foreach ($products as $product) {
            if ($this->validateItem($product)) {
                $items[] = new SearchProductResponse(
                    $product['activity_start_datetime'],
                    $product['places_available'],
                    $product['activity_duration_in_minutes'],
                    $product['product_id']
                );
            }
        }

        uasort($items, ['\GYG\Infrastructure\Client\PartnerClient', 'sort']);

        return $items;
    }

    public static function sort(SearchProductResponse $a, SearchProductResponse $b)
    {
        return $a->getActivityStartDatetime()->getTimestamp() - $b->getActivityStartDatetime()->getTimestamp();
    }
}

<?php

namespace GYG\Domain\Service;

use GYG\Domain\Service\Entities\SearchProductsRequest as Request;
use GYG\Infrastructure\Client\Entities\SearchProductsRequest;

class PartnerProductServiceImpl implements PartnerProductService
{
    const DEFAULT_FORMATTER = "Y-m-d\\TH:i";

    /**
     * @var \GYG\Infrastructure\Client\PartnerClient
     */
    private $partnerClient;

    /**
     * PartnerProductServiceImpl constructor.
     * @param \GYG\Infrastructure\Client\PartnerClient $partnerClient
     */
    public function __construct(\GYG\Infrastructure\Client\PartnerClient $partnerClient)
    {
        $this->partnerClient = $partnerClient;
    }


    public function searchProducts(Request $request)
    {
        $response = [];
        $requestClient = new SearchProductsRequest(
            $request->getStartTime(),
            $request->getEndTime(),
            $request->getNumberOfTravelers()
        );
        $productsClient = $this->partnerClient->search($requestClient);

        if ($productsClient->count() == 0) {
            return $response;
        }

        /** @var  $product \GYG\Infrastructure\Client\Entities\SearchProductResponse */
        foreach ($productsClient as $product) {
            $productFound = array_column($response, 'product_id');

            $key = array_search($product->getProductId(), $productFound);

            if ($key !== false) {
                $response[$key]['available_starttimes'] = $this->availabilityTimes($response, $key, $product);
                continue;
            }

            $response[] = $this->formatResponseItem($product);
        }

        return $response;
    }

    private function formatResponseItem(\GYG\Infrastructure\Client\Entities\SearchProductResponse $product)
    {
        return [
            'product_id' => $product->getProductId(),
            'available_starttimes' => [$product->getActivityStartDatetime()
                ->format(self::DEFAULT_FORMATTER)]
        ];
    }

    /**
     * @param $response
     * @param $key
     * @param $product
     * @return array
     */
    protected function availabilityTimes($response, $key, $product)
    {
        $tempAvailability = $response[$key]['available_starttimes'];
        $tempAvailability[] = $product->getActivityStartDatetime()
            ->format(self::DEFAULT_FORMATTER);
        return $tempAvailability;
    }
}

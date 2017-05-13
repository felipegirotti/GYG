<?php
/**
 * Created by PhpStorm.
 * User: felipegirotti
 * Date: 5/5/17
 * Time: 10:39 PM
 */

namespace GYG\Domain\Service;

use GYG\Domain\Service\Entities\SearchProductsRequest as Request;

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
        $productsClient = $this->partnerClient->search();
        if (!empty($productsClient)) {
            $productsFiltered = new BetweenPeriodIterator($productsClient, $request);

            /** @var  $product \GYG\Infrastructure\Client\Entities\SearchProductResponse */
            foreach ($productsFiltered as $product) {

                $productFound = array_column($response, 'product_id');

                $key = array_search($product->getProductId(), $productFound);

                if (!empty($productFound) && $key !== false) {
                    $tempAvailability = $response[$key]['available_starttimes'];
                    $tempAvailability[] = $product->getActivityStartDatetime()
                        ->format(self::DEFAULT_FORMATTER);
                    sort($tempAvailability);

                    $response[$key]['available_starttimes'][] = $tempAvailability;

                } else {
                    $response[] = [
                        'product_id' => $product->getProductId(),
                        'available_starttimes' => [$product->getActivityStartDatetime()->format(self::DEFAULT_FORMATTER)]
                    ];
                }
            }
        }

        return array_values($response);
    }
}

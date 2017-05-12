<?php

namespace GYG\Domain\Service;

use GYG\Domain\Service\Entities\SearchProductsRequest as Request;

interface PartnerProductService
{
    public function searchProducts(Request $request);
}

<?php

namespace GYG\Domain\Service;

use GYG\Domain\Service\Entities\SearchProductsRequest;

class BetweenPeriodIterator extends \FilterIterator
{

    /**
     * @var SearchProductsRequest
     */
    private $request;

    /**
     * @param \Iterator $iterator
     * @param SearchProductsRequest $request
     */
    public function __construct(\Iterator $iterator, SearchProductsRequest $request)
    {
        parent::__construct($iterator);
        $this->request = $request;
    }


    public function accept()
    {
        if (!$this->getInnerIterator()->valid()) {
            return false; // @codeCoverageIgnore
        }
        /** @var $row \GYG\Infrastructure\Client\Entities\SearchProductResponse */
        $row = $this->getInnerIterator()->current();

        return (
            $row->getActivityStartDatetime() >= $this->request->getStartTime()
            && $row->getActivityStartDatetime() <= $this->request->getEndTime()
            && $row->getPlacesAvailable() >= $this->request->getNumberOfTravelers()
        );
    }
}

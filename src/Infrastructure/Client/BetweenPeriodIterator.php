<?php

namespace GYG\Infrastructure\Client;

use GYG\Infrastructure\Client\Entities\SearchProductsRequest;

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
        /** @var array $row  */
        $row = $this->getInnerIterator()->current();
        $startTime = new \DateTime($row['activity_start_datetime']);
        return (
            $startTime >= $this->request->getStartTime()
            && $startTime <= $this->request->getEndTime()
            && $row['places_available'] >= $this->request->getNumberOfTravelers()
        );
    }
}

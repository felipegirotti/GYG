<?php

namespace GYG\Domain\Service\Entities;

class SearchProductsRequest
{
    /**
     * @var \DateTime
     */
    private $startTime;

    /**
     * @var \DateTime
     */
    private $endTime;

    /**
     * @var int
     */
    private $numberOfTravelers;

    /**
     * SearchProductsRequest constructor.
     * @param \DateTime $startTime
     * @param \DateTime $endTime
     * @param int $numberOfTravelers
     */
    public function __construct(\DateTime $startTime, \DateTime $endTime, $numberOfTravelers)
    {
        $this->startTime = $startTime;
        $this->endTime = $endTime;
        $this->numberOfTravelers = $numberOfTravelers;
    }

    /**
     * @return \DateTime
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * @return \DateTime
     */
    public function getEndTime()
    {
        return $this->endTime;
    }

    /**
     * @return int
     */
    public function getNumberOfTravelers()
    {
        return $this->numberOfTravelers;
    }
}

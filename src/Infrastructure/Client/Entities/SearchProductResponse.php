<?php

namespace GYG\Infrastructure\Client\Entities;

class SearchProductResponse
{
    private $placesAvailable;

    private $activityDurationInMinutes;

    private $productId;

    /**
     * @var \DateTime
     */
    private $activityStartDatetime;

    /**
     * SearchProductResponse constructor.
     * @param $activityStartDatetime
     * @param $placesAvailable
     * @param $activityDurationInMinutes
     * @param $productId
     */
    public function __construct($activityStartDatetime, $placesAvailable, $activityDurationInMinutes, $productId)
    {
        $this->activityStartDatetime = new \DateTime($activityStartDatetime);
        $this->placesAvailable = $placesAvailable;
        $this->activityDurationInMinutes = $activityDurationInMinutes;
        $this->productId = $productId;
    }

    /**
     * @codeCoverageIgnore
     * @return mixed
     */
    public function getPlacesAvailable()
    {
        return $this->placesAvailable;
    }

    /**
     * @return mixed
     */
    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * @return \DateTime
     */
    public function getActivityStartDatetime()
    {
        return $this->activityStartDatetime;
    }
}

<?php

namespace GYG\Domain\Service;


use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GYG\Domain\Service\Entities\SearchProductsRequest;
use GYG\Infrastructure\Client\PartnerClient;
use PHPUnit\Framework\TestCase;

class PartnerProductServiceTest extends TestCase
{

    /**
     * @var PartnerProductServiceImpl
     */
    private $service;

    private $responseDefault = '{"product_availabilities": [
            {"places_available": 25, "activity_duration_in_minutes": 255, "product_id": 679, "activity_start_datetime": "2017-07-07T10:30"},
            {"places_available": 25, "activity_duration_in_minutes": 255, "product_id": 23, "activity_start_datetime": "2017-07-05T10:30"},
            {"places_available": 63, "activity_duration_in_minutes": 915, "product_id": 197, "activity_start_datetime": "2017-12-14T13:45"},
            {"places_available": 3, "activity_duration_in_minutes": 165, "product_id": 679, "activity_start_datetime": "2017-10-07T14:45"},
            {"places_available": 3, "activity_duration_in_minutes": 165, "product_id": 679, "activity_start_datetime": "2017-07-06T14:45"},
            {"places_available": 55, "activity_duration_in_minutes": 1305, "product_id": 277, "activity_start_datetime": "2017-10-10T21:30"}
            ]}';

    private function setUpService(Response $response)
    {
        $mock = new MockHandler([
           $response
        ]);

        $handler = HandlerStack::create($mock);
        $httpClient = new Client(['handler' => $handler]);
        $partnerClient = new PartnerClient($httpClient);
        $this->service = new PartnerProductServiceImpl($partnerClient);
    }

    private function defaultResponse()
    {
        return new Response(200, ['Content-type' => 'application/json;charset=utf-8'], $this->responseDefault);
    }

    public function setUp()
    {
        $this->setUpService($this->defaultResponse());
    }

    public function testSearchProductsEmpty()
    {
        $this->setUpService(new Response(200, ['Content-type' => 'application/json;charset=utf-8'], '{"product_availabilities": []}'));
        $request = new SearchProductsRequest(new \DateTime('2017-07-04T10:20'), new \DateTime('2017-12-15T00:00'), 3);
        $response = $this->service->searchProducts($request);

        $this->assertEmpty($response);
    }

    public function testSearchProductsShouldBeAllProducts()
    {
        $request = new SearchProductsRequest(new \DateTime('2017-07-04T10:20'), new \DateTime('2017-12-15T00:00'), 3);
        $response = $this->service->searchProducts($request);

        $this->assertEquals(4, count($response));
        $this->assertEquals('23', $response[0]['product_id']);
        $this->assertEquals(3, count($response[1]['available_starttimes']));
        $this->assertEquals('2017-07-06T14:45', $response[1]['available_starttimes'][0]);
        $this->assertEquals('2017-07-07T10:30', $response[1]['available_starttimes'][1]);

    }

    public function testSearchProductsShouldBeThreeProductsWithLessStartTimes()
    {
        $request = new SearchProductsRequest(new \DateTime('2017-07-07T10:20'), new \DateTime('2017-12-15T00:00'), 4);
        $response = $this->service->searchProducts($request);

        $this->assertEquals(3, count($response));
        $this->assertEquals('679', $response[0]['product_id']);
        $this->assertEquals(1, count($response[0]['available_starttimes']));
        $this->assertEquals('2017-07-07T10:30', $response[0]['available_starttimes'][0]);
    }

    public function testSearchProductsShouldBeOTwoProduct()
    {
        $request = new SearchProductsRequest(new \DateTime('2017-10-07T10:20'), new \DateTime('2017-11-15T00:00'), 3);
        $response = $this->service->searchProducts($request);
        $this->assertEquals(2, count($response));
        $this->assertEquals('679', $response[0]['product_id']);
        $this->assertEquals(1, count($response[0]['available_starttimes']));
        $this->assertEquals('2017-10-07T14:45', $response[0]['available_starttimes'][0]);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testSearchProductsShouldBeRaiseException()
    {
        $this->setUpService(new Response(502, [], 'Bad Gateway'));
        $request = new SearchProductsRequest(new \DateTime('2017-07-07T10:20'), new \DateTime('2017-12-15T00:00'), 4);
        $this->service->searchProducts($request);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testSearchProductsErrorJsonShouldBeRaiseException()
    {
        $this->setUpService(new Response(200, [], '{}'));
        $request = new SearchProductsRequest(new \DateTime('2017-07-07T10:20'), new \DateTime('2017-12-15T00:00'), 4);
        $this->service->searchProducts($request);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testSearchProductsErrorParametersShouldBeRaiseException()
    {
        $this->setUpService(new Response(200, [], '{}'));
        $request = new SearchProductsRequest(new \DateTime('2017-07-07T10:20'), new \DateTime('2017-12-15T00:00'), 4);
        $this->service->searchProducts($request);
    }
}
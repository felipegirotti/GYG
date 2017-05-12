<?php

date_default_timezone_set('UTC');

require_once __DIR__ . '/vendor/autoload.php';

\GYG\Domain\Validator\InputParamsValidator::validate($argv);

$httpClient = new \GuzzleHttp\Client(['base_uri' => $argv[1]]);
$client = new \GYG\Infrastructure\Client\PartnerClient($httpClient);
$service = new \GYG\Domain\Service\PartnerProductServiceImpl($client);

$request = new \GYG\Domain\Service\Entities\SearchProductsRequest(new \DateTime($argv[2]), new DateTime($argv[3]), $argv[4]);

$response = $service->searchProducts($request);

echo json_encode($response, JSON_PRETTY_PRINT);
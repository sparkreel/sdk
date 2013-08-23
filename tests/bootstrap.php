<?php
/**
 * User: Tabaré Caorsi <tabare@heapstersoft.com>
 * Date: 8/21/13
 * Time: 10:19 AM
 *
 * Boostrap the php unit environment
 */

require_once realpath(dirname(__FILE__)."/../vendor/autoload.php");

$serviceBuilder = new Guzzle\Service\Builder\ServiceBuilder();
$serviceBuilder->set('test.sparkreel', \Sparkreel\Sdk\SparkreelClient::factory(array(
    "base_url"=>"https://api.sparkreel/v1",
    'ssl.certificate_authority' => 'system',
    'curl.options' => array(
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_SSL_VERIFYHOST => 0
    ))));

Guzzle\Tests\GuzzleTestCase::setServiceBuilder($serviceBuilder);

//Set response mocks directory
Guzzle\Tests\GuzzleTestCase::setMockBasePath(__DIR__ . '/mock');

define("TEST_VIDEOS_PATH", __DIR__ . '/videos');
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
$serviceBuilder->set('test.sparkreel', \Sparkreel\Sdk\SparkreelClient::factory());

Guzzle\Tests\GuzzleTestCase::setServiceBuilder($serviceBuilder);

//Set response mocks directory
Guzzle\Tests\GuzzleTestCase::setMockBasePath(__DIR__ . '/mock');
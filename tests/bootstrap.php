<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Tabaré Caorsi <tabare@heapstersoft.com>
 * Date: 8/21/13
 * Time: 10:19 AM
 * To change this template use File | Settings | File Templates.
 */

require_once realpath(dirname(__FILE__)."/../vendor/autoload.php");

error_reporting(E_ALL | E_STRICT);

Guzzle\Tests\GuzzleTestCase::setServiceBuilder(Aws\Common\Aws::factory($_SERVER['CONFIG']));

Guzzle\Tests\GuzzleTestCase::setServiceBuilder(Guzzle\Service\Builder\ServiceBuilder::factory(array(
    'test.unfuddle' => array(
        'class' => 'Guzzle.Unfuddle.UnfuddleClient',
        'params' => array(

        )
    )
)));
<?php

declare(strict_types=1);

/**
 *
 * NOTICE OF LICENSE
 *
 * This source file is released under GNU General Public License v2.
 *
 * @copyright 1999-2005 easyDNS Technologies Inc. & Mark Jeftovic
 * @copyright xxxx-xxxx Maintained by David Saez
 * @copyright 2014-2019 Dmitry Lukashin
 * @copyright 2019-2020 Niko Granö (https://granö.fi)
 *
 */

use phpWhois\DomainHandlerMap;
use phpWhois\Handler\Jp;

class DomainHandlerMapTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test that findHandler returns valid classname.
     *
     * @param $address
     * @param $className
     * @dataProvider addressesProvider
     */
    public function testFindHandler($address, $className): void
    {
        $this->assertEquals($className, (new DomainHandlerMap())->findHandler($address));
    }

    /**
     * Addresses provider for finding handler.
     *
     * @return array
     */
    public function addressesProvider()
    {
        return [
            ['www.google.jp', Jp::class],
            ['www.google.co.jp', Jp::class],
        ];
    }

    /**
     * Test that findHandler method returns null when handler not found.
     *
     * @param $query
     * @dataProvider queryProviderNotDomain
     */
    public function testFindHandlerNotDomain($query): void
    {
        $this->assertNull((new DomainHandlerMap())->findHandler($query));
    }

    /**
     * Missing handlers provider.
     *
     * @return array
     */
    public function queryProviderNotDomain()
    {
        return [
            ['212.12.212.12'],
        ];
    }
}

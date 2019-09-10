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

use phpWhois\Provider\ProviderAbstract;
use phpWhois\Query;

class ProviderAbstractMock extends ProviderAbstract
{
    protected $port = 77;

    public function connect()
    {
        $this->setConnectionErrNo(0);

        return $this;
    }

    public function setConnectionPointer($pointer)
    {
        return parent::setConnectionPointer($pointer);
    }

    public function isConnected()
    {
        return parent::isConnected();
    }

    public function performRequest()
    {
        return $this->getQuery()->getAddressOrig();
    }
}

class ProviderAbstractTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider constructProvider
     */
    public function testConstructor($server, $serverE, $portE): void
    {
        $provider = new ProviderAbstractMock(new Query('www.Google.com'), $server);

        $this->assertInstanceOf(Query::class, $provider->getQuery());
        $this->assertTrue($provider->getQuery()->hasData());
        $this->assertEquals($provider->getServer(), $serverE);
        $this->assertEquals($provider->getPort(), $portE);
    }

    public function constructProvider()
    {
        return [
            ['whois.nic.ru', 'whois.nic.ru', 77],
            ['whois.nic.ru:55', 'whois.nic.ru', 55],
        ];
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testConstructorWithEmptyQuery(): void
    {
        $provider = new ProviderAbstractMock(new Query(), 'whois.iana.org');
    }

    public function testLookup(): void
    {
        $address = 'www.Google.COM';

        $provider = new ProviderAbstractMock(new Query($address), 'whois.iana.org');
        $provider->lookup();

        $this->assertEquals($address, $provider->getQuery()->getAddressOrig());
    }

    public function testIsConnected(): void
    {
        $provider = new ProviderAbstractMock(new Query('www.google.com'), 'whois.iana.org');
        $provider->setConnectionPointer(true);
        $this->assertTrue($provider->isConnected());
    }
}

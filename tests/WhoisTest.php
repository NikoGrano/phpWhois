<?php

declare(strict_types=1);

/**
 *
 * NOTICE OF LICENSE
 *
 * This source file is released under GNU General Public License v2.
 *
 * @copyright 1999-2005 easyDNS Technologies Inc. & Mark Jeftovic
 * @copyright 2005-2014 David Saez
 * @copyright 2014-2019 Dmitry Lukashin
 * @copyright 2019-2020 Niko Granö (https://granö.fi)
 *
 */

use phpWhois\Whois;
use phpWhois\Handler\HandlerBase;

/**
 * Class WhoisTest.
 */
class WhoisTest extends PHPUnit_Framework_TestCase
{
    protected $whois;

    protected function setUp(): void
    {
        $this->whois = new Whois('google.com');
    }

    /**
     * Pass null as a handler class name.
     */
    public function testSetHandlerNull(): void
    {
        $this->assertInstanceOf(Whois::class, $this->whois->setHandler(null));
    }

    /**
     * Try to assign a handler while query is not set.
     *
     * @expectedException \InvalidArgumentException
     */
    public function testSetHandlerEmptyQuery(): void
    {
        $this->markTestIncomplete('Should handler test double be here?');
        $whois = new Whois();
        $whois->setHandler(HandlerBase::class);
    }

    /**
     * Try to set correct handler.
     */
    public function testSetHandler(): void
    {
        $this->markTestIncomplete('Should handler test double be here?');

        $method = new \ReflectionMethod(Whois::class, 'getHandler');
        $method->setAccessible(true);

        $this->whois->setHandler(HandlerBase::class);

        $this->assertInstanceOf(HandlerBase::class, $method->invoke($this->whois, 'getHandler'));
    }

    /**
     * Set handler of wrong type.
     *
     * @expectedException \InvalidArgumentException
     */
    public function testSetHandlerWrongType(): void
    {
        $this->markTestIncomplete('Should test double be here?');

        $method = new \ReflectionMethod(Whois::class, 'getHandler');
        $method->setAccessible(true);

        $this->whois->setHandler('stdClass');

        $this->assertInstanceOf(HandlerBase::class, $method->invoke($this->whois, 'getHandler'));
    }

    /*
     * TODO: lookup test
     */
}

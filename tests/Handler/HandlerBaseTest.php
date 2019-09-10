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

use phpWhois\Handler\HandlerBase;
use phpWhois\Provider\ProviderAbstract;
use phpWhois\Query;

class HandlerBaseMock extends HandlerBase
{
    public $server = 'whois.server.test';
}

class HandlerBaseTest extends \PHPUnit_Framework_TestCase
{
    protected $query;
    protected $handler;

    public function setUp(): void
    {
        $this->query = new Query('www.goOgle.com');
        $this->handler = new HandlerBaseMock($this->query);
    }

    public function testConstructorWithoutServer(): void
    {
        $handler = new HandlerBaseMock($this->query);

        $this->assertEquals('whois.server.test', $handler->server);

        $this->assertInstanceOf(ProviderAbstract::class, $handler->getProvider());
    }

    /**
     * Test constructor with all possible parameters.
     */
    public function testConstructorWithServer(): void
    {
        $server = 'special.whois.server.test';

        $handler = new HandlerBaseMock($this->query, $server);

        $this->assertEquals($server, $handler->server);

        $this->assertInstanceOf(ProviderAbstract::class, $handler->getProvider());
    }

    /**
     * TODO: Test Provider setting by class name.
     */

    /**
     * Test splitting raw data by newline.
     *
     * @param $raw  Raw data
     * @param $count Number of rows
     * @dataProvider rawProvider
     */
    public function testSplitRows($raw, $count): void
    {
        $this->assertCount($count, $this->handler->splitRows($raw));
    }

    public function rawProvider()
    {
        return [
            ["line1\nline2\nline3", 3],
            ["line1\r\nline2\r\nline3", 3],
            ["line1\r\nline2\r\nline3", 3],
            ["line1\r\n\r\nline2\r\nline3", 4],
            ["line1\r\n\n\r\nline2\r\nline3", 5],
        ];
    }
}

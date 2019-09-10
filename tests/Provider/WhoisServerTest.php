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

use phpWhois\Provider\WhoisServer;
use phpWhois\Query;

class WhoisServerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider constructProvider
     */
    public function testConstruct($server, $parsed): void
    {
        $w = new WhoisServer(new Query('www.google.ru'), $server);

        $this->assertEquals($parsed, ['server' => $w->getServer(), 'port' => $w->getPort()]);
    }

    public function constructProvider()
    {
        return [
            ['whois.nic.ru', ['server' => 'whois.nic.ru', 'port' => 43]],
            ['whois.nic.ru:55', ['server' => 'whois.nic.ru', 'port' => 55]],
        ];
    }
}

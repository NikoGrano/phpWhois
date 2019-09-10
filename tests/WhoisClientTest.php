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

use phpWhois\WhoisClient;

class WhoisClientTest extends \PHPUnit_Framework_TestCase
{
    /*public function testVersion()
    {
        $client = new WhoisClient;
        $this->assertRegExp('/^(\d+)\.(\d+)\.(\d+)(-\w+)*$/', $client->codeVersion);
    }*/

    /**
     * @dataProvider serversProvider
     */
    public function testParseServer($server, $result): void
    {
        $whoisClient = new WhoisClient();
        $this->assertEquals($result, $whoisClient->parseServer($server));
    }

    public function serversProvider()
    {
        return [
            ['http://www.phpwhois.pw:80/', ['scheme' => 'http', 'host' => 'www.phpwhois.pw', 'port' => 80]],
            ['http://www.phpwhois.pw:80', ['scheme' => 'http', 'host' => 'www.phpwhois.pw', 'port' => 80]],
            ['http://www.phpwhois.pw', ['scheme' => 'http', 'host' => 'www.phpwhois.pw']],
            ['www.phpwhois.pw:80', ['host' => 'www.phpwhois.pw', 'port' => 80]],
            ['www.phpwhois.pw:80/', ['host' => 'www.phpwhois.pw', 'port' => 80]],
            ['www.phpwhois.pw', ['host' => 'www.phpwhois.pw']],
            ['www.phpwhois.pw/', ['host' => 'www.phpwhois.pw']],
            ['http://127.0.0.1:80/', ['scheme' => 'http', 'host' => '127.0.0.1', 'port' => 80]],
            ['http://127.0.0.1:80', ['scheme' => 'http', 'host' => '127.0.0.1', 'port' => 80]],
            ['http://127.0.0.1', ['scheme' => 'http', 'host' => '127.0.0.1']],
            ['127.0.0.1:80', ['host' => '127.0.0.1', 'port' => 80]],
            ['127.0.0.1:80/', ['host' => '127.0.0.1', 'port' => 80]],
            ['127.0.0.1', ['host' => '127.0.0.1']],
            ['127.0.0.1/', ['host' => '127.0.0.1']],
            ['http://[1a80:1f45::ebb:12]:80/', ['scheme' => 'http', 'host' => '[1a80:1f45::ebb:12]', 'port' => 80]],
            ['http://[1a80:1f45::ebb:12]:80', ['scheme' => 'http', 'host' => '[1a80:1f45::ebb:12]', 'port' => 80]],
            ['http://[1a80:1f45::ebb:12]', ['scheme' => 'http', 'host' => '[1a80:1f45::ebb:12]']],
            //['http://1a80:1f45::ebb:12', [scheme' => 'http', 'host' => '[1a80:1f45::ebb:12]']],
            ['[1a80:1f45::ebb:12]:80', ['host' => '[1a80:1f45::ebb:12]', 'port' => 80]],
            ['[1a80:1f45::ebb:12]:80/', ['host' => '[1a80:1f45::ebb:12]', 'port' => 80]],
            ['1a80:1f45::ebb:12', ['host' => '[1a80:1f45::ebb:12]']],
            ['1a80:1f45::ebb:12/', ['host' => '[1a80:1f45::ebb:12]']],
        ];
    }
}

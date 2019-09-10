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

if (!\defined('__PL_HANDLER__')) {
    \define('__PL_HANDLER__', 1);
}

require_once 'whois.parser.php';

class pl_handler
{
    public function parse($data_str, $query)
    {
        $items = [
            'domain.created' => 'created:',
            'domain.changed' => 'last modified:',
            'domain.sponsor' => 'REGISTRAR:',
            '#'              => 'WHOIS displays data with a delay not exceeding 15 minutes in relation to the .pl Registry system',
        ];

        $r = [];
        $r['regrinfo'] = easy_parser($data_str['rawdata'], $items, 'ymd');

        $r['regyinfo'] = [
            'referrer'  => 'http://www.dns.pl/english/index.html',
            'registrar' => 'NASK',
        ];

        return $r;
    }
}

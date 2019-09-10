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

if (!\defined('__IANA_HANDLER__')) {
    \define('__IANA_HANDLER__', 1);
}

require_once 'whois.parser.php';

class iana_handler
{
    public function parse($data_str, $query)
    {
        $items = [
            'admin'           => 'contact:      administrative',
            'tech'            => 'contact:      technical',
            'domain.nserver.' => 'nserver:',
            'domain.created'  => 'created:',
            'domain.changed'  => 'changed:',
            'domain.source'   => 'source:',
            'domain.name'     => 'domain:',
            'disclaimer.'     => '% ',
        ];

        return easy_parser($data_str, $items, 'Ymd', [], false, false, 'owner');
    }
}

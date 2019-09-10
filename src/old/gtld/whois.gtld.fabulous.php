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

if (!\defined('__FABULOUS_HANDLER__')) {
    \define('__FABULOUS_HANDLER__', 1);
}

require_once 'whois.parser.php';

class fabulous_handler
{
    public function parse($data_str, $query)
    {
        $items = [
            'owner'   => 'Domain '.$query.':',
            'admin'   => 'Administrative contact:',
            'tech'    => 'Technical contact:',
            'billing' => 'Billing contact:',
            ''        => 'Record dates:',
        ];

        $r = easy_parser($data_str, $items, 'mdy', [], false, true);

        if (!isset($r['tech'])) {
            $r['tech'] = $r['billing'];
        }

        if (!isset($r['admin'])) {
            $r['admin'] = $r['tech'];
        }

        return $r;
    }
}

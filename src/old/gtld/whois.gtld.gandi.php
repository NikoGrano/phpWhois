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

if (!\defined('__GANDI_HANDLER__')) {
    \define('__GANDI_HANDLER__', 1);
}

require_once 'whois.parser.php';

class gandi_handler
{
    public function parse($data_str, $query)
    {
        $items = [
            'owner'   => 'owner-c',
            'admin'   => 'admin-c',
            'tech'    => 'tech-c',
            'billing' => 'bill-c',
        ];

        $trans = [
            'nic-hdl:'     => 'handle',
            'person:'      => 'name',
            'zipcode:'     => 'address.pcode',
            'city:'        => 'address.city',
            'lastupdated:' => 'changed',
            'owner-name:'  => '',
        ];

        return easy_parser($data_str, $items, 'dmy', $trans);
    }
}

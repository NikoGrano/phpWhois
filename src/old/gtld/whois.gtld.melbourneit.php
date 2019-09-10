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

require_once 'whois.parser.php';

if (!\defined('__MELBOURNEIT_HANDLER__')) {
    \define('__MELBOURNEIT_HANDLER__', 1);
}

class melbourneit_handler
{
    public function parse($data_str, $query)
    {
        $items = [
            'Domain Name..........' => 'domain.name',
            'Registration Date....' => 'domain.created',
            'Expiry Date..........' => 'domain.expires',
            'Organisation Name....' => 'owner.name',
            'Organisation Address.' => 'owner.address.',
            'Admin Name...........' => 'admin.name',
            'Admin Address........' => 'admin.address.',
            'Admin Email..........' => 'admin.email',
            'Admin Phone..........' => 'admin.phone',
            'Admin Fax............' => 'admin.fax',
            'Tech Name............' => 'tech.name',
            'Tech Address.........' => 'tech.address.',
            'Tech Email...........' => 'tech.email',
            'Tech Phone...........' => 'tech.phone',
            'Tech Fax.............' => 'tech.fax',
            'Name Server..........' => 'domain.nserver.',
        ];

        return generic_parser_b($data_str, $items, 'ymd');
    }
}

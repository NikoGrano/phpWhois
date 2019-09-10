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

if (!\defined('__GODADDY_HANDLER__')) {
    \define('__GODADDY_HANDLER__', 1);
}

require_once 'whois.parser.php';

class godaddy_handler
{
    public function parse($data_str, $query)
    {
        $items = [
            'owner'           => 'Registrant:',
            'admin'           => 'Administrative Contact',
            'tech'            => 'Technical Contact',
            'domain.name'     => 'Domain Name:',
            'domain.nserver.' => 'Domain servers in listed order:',
            'domain.created'  => 'Created on:',
            'domain.expires'  => 'Expires on:',
            'domain.changed'  => 'Last Updated on:',
            'domain.sponsor'  => 'Registered through:',
        ];

        $r = get_blocks($data_str, $items);
        $r['owner'] = get_contact($r['owner']);
        $r['admin'] = get_contact($r['admin'], [], true);
        $r['tech'] = get_contact($r['tech'], [], true);

        return format_dates($r, 'dmy');
    }
}

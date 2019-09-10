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

if (!\defined('__NICCO_HANDLER__')) {
    \define('__NICCO_HANDLER__', 1);
}

require_once 'whois.parser.php';

class nicco_handler
{
    public function parse($data_str, $query)
    {
        $items = [
            'owner'           => 'Holder Contact',
            'admin'           => 'Admin Contact',
            'tech'            => 'Tech. Contact',
            'domain.nserver.' => 'Nameservers',
            'domain.created'  => 'Creation Date:',
            'domain.expires'  => 'Expiration Date:',
        ];

        $translate = [
            'city:'        => 'address.city',
            'org. name:'   => 'organization',
            'address1:'    => 'address.street.',
            'address2:'    => 'address.street.',
            'state:'       => 'address.state',
            'postal code:' => 'address.zip',
        ];

        $r = get_blocks($data_str, $items, true);
        $r['owner'] = get_contact($r['owner'], $translate);
        $r['admin'] = get_contact($r['admin'], $translate, true);
        $r['tech'] = get_contact($r['tech'], $translate, true);

        return format_dates($r, 'dmy');
    }
}

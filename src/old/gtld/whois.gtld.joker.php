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

if (!\defined('__JOKER_HANDLER__')) {
    \define('__JOKER_HANDLER__', 1);
}

require_once 'whois.parser.php';

class joker_handler
{
    public function parse($data_str, $query)
    {
        $translate = [
            'contact-hdl' => 'handle',
            'modified'    => 'changed',
            'reseller'    => 'sponsor',
            'address'     => 'address.street',
            'postal-code' => 'address.pcode',
            'city'        => 'address.city',
            'state'       => 'address.state',
            'country'     => 'address.country',
            'person'      => 'name',
            'domain'      => 'name',
        ];

        $contacts = [
            'admin-c'   => 'admin',
            'tech-c'    => 'tech',
            'billing-c' => 'billing',
        ];

        $items = [
            'owner'        => 'name',
            'organization' => 'organization',
            'email'        => 'email',
            'phone'        => 'phone',
            'address'      => 'address',
        ];

        $r = generic_parser_a($data_str, $translate, $contacts, 'domain', 'Ymd');

        foreach ($items as $tag => $convert) {
            if (isset($r['domain'][$tag])) {
                $r['owner'][$convert] = $r['domain'][$tag];
                unset($r['domain'][$tag]);
            }
        }

        return $r;
    }
}

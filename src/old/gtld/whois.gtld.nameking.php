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

if (!\defined('__NAMEKING_HANDLER__')) {
    \define('__NAMEKING_HANDLER__', 1);
}

require_once 'whois.parser.php';

class nameking_handler
{
    public function parse($data_str, $query)
    {
        $items = [
            'owner'          => 'Registrant',
            'admin'          => 'Admin Contact',
            'tech'           => 'Tech Contact',
            'billing'        => 'Billing Contact',
            'domain.sponsor' => 'Registration Provided By:',
            'domain.created' => 'Creation Date:',
            'domain.expires' => 'Expiration Date:',
        ];

        $extra = [
            'tel--'                      => 'phone',
            'tel:'                       => 'phone',
            'tel --:'                    => 'phone',
            'email-:'                    => 'email',
            'email:'                     => 'email',
            'mail:'                      => 'email',
            'name--'                     => 'name',
            'org:'                       => 'organization',
            'zipcode:'                   => 'address.pcode',
            'postcode:'                  => 'address.pcode',
            'address:'                   => 'address.street',
            'city:'                      => 'address.city',
            'province:'                  => 'address.city.',
            ',province:'                 => '',
            ',country:'                  => 'address.country',
            'organization:'              => 'organization',
            'city, province, post code:' => 'address.city',
        ];

        return easy_parser($data_str, $items, 'mdy', $extra, false, true);
    }
}

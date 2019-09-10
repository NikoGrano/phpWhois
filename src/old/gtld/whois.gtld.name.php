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

if (!\defined('__NAME_HANDLER__')) {
    \define('__NAME_HANDLER__', 1);
}

require_once 'whois.parser.php';

class name_handler
{
    public function parse($data_str, $query)
    {
        $items = [
            'owner'          => 'REGISTRANT CONTACT INFO',
            'admin'          => 'ADMINISTRATIVE CONTACT INFO',
            'tech'           => 'TECHNICAL CONTACT INFO',
            'billing'        => 'BILLING CONTACT INFO',
            'domain.name'    => 'Domain Name:',
            'domain.sponsor' => 'Registrar',
            'domain.created' => 'Creation Date',
            'domain.expires' => 'Expiration Date',
        ];

        $extra = [
            'phone:'         => 'phone',
            'email address:' => 'email',
        ];

        return easy_parser($data_str, $items, 'y-m-d', $extra, false, true);
    }
}

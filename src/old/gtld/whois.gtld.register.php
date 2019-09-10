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

if (!\defined('__REGISTER_HANDLER__')) {
    \define('__REGISTER_HANDLER__', 1);
}

require_once 'whois.parser.php';

class register_handler
{
    public function parse($data_str, $query)
    {
        $items = [
            'owner#0'          => 'Registrant Info:',
            'owner#1'          => 'Organization:',
            'owner#2'          => 'Registrant:',
            'owner#3'          => 'Registrant Contact:',
            'admin'            => 'Administrative',
            'tech'             => 'Technical',
            'zone'             => 'Zone',
            'domain.sponsor#0' => 'Registrar Name....:',
            'domain.sponsor#1' => 'Registration Service Provided By:',
            'domain.referrer'  => 'Registrar Homepage:',
            'domain.nserver'   => 'Domain servers in listed order:',
            'domain.nserver'   => 'DNS Servers:',
            'domain.name'      => 'Domain name:',
            'domain.created#0' => 'Created on..............:',
            'domain.created#1' => 'Creation date:',
            'domain.expires#0' => 'Expires on..............:',
            'domain.expires#1' => 'Expiration date:',
            'domain.changed'   => 'Record last updated on..:',
            'domain.status'    => 'Status:',
        ];

        return easy_parser($data_str, $items, 'ymd');
    }
}

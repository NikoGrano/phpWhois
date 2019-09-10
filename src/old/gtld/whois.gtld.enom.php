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

if (!\defined('__ENOM_HANDLER__')) {
    \define('__ENOM_HANDLER__', 1);
}

require_once 'whois.parser.php';

class enom_handler
{
    public function parse($data_str, $query)
    {
        $items = [
            'owner#0'          => 'Registrant Contact',
            'owner#1'          => 'REGISTRANT Contact:',
            'admin#0'          => 'Administrative Contact',
            'admin#1'          => 'ADMINISTRATIVE Contact:',
            'tech#0'           => 'Technical Contact',
            'tech#1'           => 'TECHNICAL Contact:',
            'billing#0'        => 'Billing Contact',
            'billing#1'        => 'BILLING Contact:',
            'domain.nserver'   => 'Nameservers',
            'domain.name#0'    => 'Domain name:',
            'domain.name#1'    => 'Domain name-',
            'domain.sponsor'   => 'Registration Service Provided By:',
            'domain.status'    => 'Status:',
            'domain.created#0' => 'Creation date:',
            'domain.expires#0' => 'Expiration date:',
            'domain.created#1' => 'Created:',
            'domain.expires#1' => 'Expires:',
            'domain.created#2' => 'Start of registration-',
            'domain.expires#2' => 'Registered through-',
        ];

        return easy_parser($data_str, $items, 'dmy', [], false, true);
    }
}

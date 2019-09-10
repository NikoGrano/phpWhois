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

if (!\defined('__PUBLICDOMAINREGISTRY_HANDLER__')) {
    \define('__PUBLICDOMAINREGISTRY_HANDLER__', 1);
}

require_once 'whois.parser.php';

class publicdomainregistry_handler
{
    public function parse($data_str, $query)
    {
        $items = [
            'owner'           => 'Registrant:',
            'owner#'          => '(Registrant):',
            'admin'           => 'Administrative Contact',
            'tech'            => 'Technical Contact',
            'billing'         => 'Billing Contact',
            'domain.name'     => 'Domain name:',
            'domain.sponsor'  => 'Registration Service Provided By:',
            'domain.nserver'  => 'Domain servers in listed order:',
            'domain.changed'  => 'Record last updated ',
            'domain.created'  => 'Record created on',
            'domain.created#' => 'Creation Date:',
            'domain.expires'  => 'Record expires on',
            'domain.expires#' => 'Expiration Date:',
            'domain.status'   => 'Status:',
        ];

        return easy_parser($data_str, $items, 'mdy', [], true, true);
    }
}

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

if (!\defined('__AFTERNIC_HANDLER__')) {
    \define('__AFTERNIC_HANDLER__', 1);
}

require_once 'whois.parser.php';

class afternic_handler
{
    public function parse($data_str, $query)
    {
        $items = [
            'owner'          => 'Registrant:',
            'admin'          => 'Administrative Contact',
            'tech'           => 'Technical Contact',
            'zone'           => 'Zone Contact',
            'domain.name'    => 'Domain Name:',
            'domain.changed' => 'Last updated on',
            'domain.created' => 'Domain created on',
            'domain.expires' => 'Domain expires on',
        ];

        return easy_parser($data_str, $items, 'dmy', [], false, true);
    }
}

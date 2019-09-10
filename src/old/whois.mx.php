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

if (!\defined('__MX_HANDLER__')) {
    \define('__MX_HANDLER__', 1);
}

require_once 'whois.parser.php';

class mx_handler
{
    public function parse($data_str, $query)
    {
        $items = [
            'owner'          => 'Registrant:',
            'admin'          => 'Administrative Contact:',
            'tech'           => 'Technical Contact:',
            'billing'        => 'Billing Contact:',
            'domain.nserver' => 'Name Servers:',
            'domain.created' => 'Created On:',
            'domain.expires' => 'Expiration Date:',
            'domain.changed' => 'Last Updated On:',
            'domain.sponsor' => 'Registrar:',
        ];

        $extra = [
            'city:'  => 'address.city',
            'state:' => 'address.state',
            'dns:'   => '0',
        ];

        $r = [];
        $r['regrinfo'] = easy_parser($data_str['rawdata'], $items, 'dmy', $extra);

        $r['regyinfo'] = [
            'registrar' => 'NIC Mexico',
            'referrer'  => 'http://www.nic.mx/',
        ];

        if (empty($r['regrinfo']['domain']['created'])) {
            $r['regrinfo']['registered'] = 'no';
        } else {
            $r['regrinfo']['registered'] = 'yes';
        }

        return $r;
    }
}

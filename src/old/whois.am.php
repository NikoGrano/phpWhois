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

if (!\defined('__AM_HANDLER__')) {
    \define('__AM_HANDLER__', 1);
}

require_once 'whois.parser.php';

class am_handler
{
    public function parse($data_str, $query)
    {
        $items = [
            'owner'          => 'Registrant:',
            'domain.name'    => 'Domain name:',
            'domain.created' => 'Registered:',
            'domain.changed' => 'Last modified:',
            'domain.nserver' => 'DNS servers:',
            'domain.status'  => 'Status:',
            'tech'           => 'Technical contact:',
            'admin'          => 'Administrative contact:',
        ];

        $r = [];
        $r['regrinfo'] = get_blocks($data_str['rawdata'], $items);

        if (!empty($r['regrinfo']['domain']['name'])) {
            $r['regrinfo'] = get_contacts($r['regrinfo']);
            $r['regrinfo']['registered'] = 'yes';
        } else {
            $r = '';
            $r['regrinfo']['registered'] = 'no';
        }

        $r['regyinfo'] = [
            'referrer'  => 'http://www.isoc.am',
            'registrar' => 'ISOCAM',
        ];

        return $r;
    }
}

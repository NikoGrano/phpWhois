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

require_once 'whois.parser.php';

if (!\defined('__LY_HANDLER__')) {
    \define('__LY_HANDLER__', 1);
}

class ly_handler
{
    public function parse($data_str, $query)
    {
        $items = [
            'owner'          => 'Registrant:',
            'admin'          => 'Administrative Contact:',
            'tech'           => 'Technical Contact:',
            'domain.name'    => 'Domain Name:',
            'domain.status'  => 'Domain Status:',
            'domain.created' => 'Created:',
            'domain.changed' => 'Updated:',
            'domain.expires' => 'Expired:',
            'domain.nserver' => 'Domain servers in listed order:',
        ];

        $extra = ['zip/postal code:' => 'address.pcode'];

        $r = [];
        $r['regrinfo'] = get_blocks($data_str['rawdata'], $items);

        if (!empty($r['regrinfo']['domain']['name'])) {
            $r['regrinfo'] = get_contacts($r['regrinfo'], $extra);
            $r['regrinfo']['domain']['name'] = $r['regrinfo']['domain']['name'][0];
            $r['regrinfo']['registered'] = 'yes';
        } else {
            $r = ['regrinfo' => []];
            $r['regrinfo']['registered'] = 'no';
        }

        $r['regyinfo'] = [
            'referrer'  => 'http://www.nic.ly',
            'registrar' => 'Libya ccTLD',
        ];

        return $r;
    }
}

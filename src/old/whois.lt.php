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

if (!\defined('__LT_HANDLER__')) {
    \define('__LT_HANDLER__', 1);
}

require_once 'whois.parser.php';

class lt_handler
{
    public function parse($data_str, $query)
    {
        $translate = [
            'contact nic-hdl:' => 'handle',
            'contact name:'    => 'name',
        ];

        $items = [
            'admin'           => 'Contact type:      Admin',
            'tech'            => 'Contact type:      Tech',
            'zone'            => 'Contact type:      Zone',
            'owner.name'      => 'Registrar:',
            'owner.email'     => 'Registrar email:',
            'domain.status'   => 'Status:',
            'domain.created'  => 'Registered:',
            'domain.changed'  => 'Last updated:',
            'domain.nserver.' => 'NS:',
            ''                => '%',
        ];

        $r = [];
        $r['regrinfo'] = easy_parser($data_str['rawdata'], $items, 'ymd', $translate);

        $r['regyinfo'] = [
            'referrer'  => 'http://www.domreg.lt',
            'registrar' => 'DOMREG.LT',
        ];

        return $r;
    }
}

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

if (!\defined('__HU_HANDLER__')) {
    \define('__HU_HANDLER__', 1);
}

require_once 'whois.parser.php';

class hu_handler
{
    public function parse($data_str, $query)
    {
        $items = [
            'domain:'         => 'domain.name',
            'record created:' => 'domain.created',
        ];

        $r = [];
        $r['regrinfo'] = generic_parser_b($data_str['rawdata'], $items, 'ymd');

        if (isset($r['regrinfo']['domain'])) {
            $r['regrinfo']['registered'] = 'yes';
        } else {
            $r['regrinfo']['registered'] = 'no';
        }

        $r['regyinfo'] = ['referrer' => 'http://www.nic.hu', 'registrar' => 'HUNIC'];

        return $r;
    }
}

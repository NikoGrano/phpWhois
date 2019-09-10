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

if (!\defined('__BH_HANDLER__')) {
    \define('__BH_HANDLER__', 1);
}

require_once 'whois.parser.php';

class bh_handler
{
    public function parse($data_str, $query)
    {
        $items = [
            'Sponsoring Registrar Name:'  => 'domain.sponsor.name',
            'Sponsoring Registrar Email:' => 'domain.sponsor.email',
            'Sponsoring Registrar Uri:'   => 'domain.sponsor.uri',
            'Sponsoring Registrar Phone:' => 'domain.sponsor.phone',
        ];
        $i = generic_parser_b($data_str['rawdata'], $items);

        $r = [];
        $r['regrinfo'] = generic_parser_b($data_str['rawdata']);
        if (isset($r['regrinfo']['domain']) && \is_array($r['regrinfo']['domain'])) {
            $r['regrinfo']['domain']['sponsor'] = $i['domain']['sponsor'];
        }
        if (empty($r['regrinfo']['domain']['created'])) {
            $r['regrinfo']['registered'] = 'no';
        } else {
            $r['regrinfo']['registered'] = 'yes';
        }
        $r['regyinfo'] = [
            'referrer'  => 'http://www.nic.bh/',
            'registrar' => 'NIC-BH',
        ];

        return $r;
    }
}

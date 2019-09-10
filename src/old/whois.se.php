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

if (!\defined('__SE_HANDLER__')) {
    \define('__SE_HANDLER__', 1);
}

require_once 'whois.parser.php';

class se_handler
{
    public function parse($data_str, $query)
    {
        $items = [
            'domain'   => 'domain.name',
            'state:'   => 'domain.status.',
            'status:'  => 'domain.status.',
            'expires:' => 'domain.expires',
            'created:' => 'domain.created',
            'nserver:' => 'domain.nserver.',
            'holder:'  => 'owner.handle',
        ];

        $r = [];
        $r['regrinfo'] = generic_parser_b($data_str['rawdata'], $items, 'ymd', false);

        $r['regrinfo']['registered'] = isset($r['regrinfo']['domain']['name']) ? 'yes' : 'no';

        $r['regyinfo'] = [
            'referrer'  => 'http://www.nic-se.se',
            'registrar' => 'NIC-SE',
        ];

        return $r;
    }
}

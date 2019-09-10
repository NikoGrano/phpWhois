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

if (!\defined('__AE_HANDLER__')) {
    \define('__AE_HANDLER__', 1);
}

require_once 'whois.parser.php';

class ae_handler
{
    public function parse($data_str, $query)
    {
        $items = [
            'Domain Name:'             => 'domain.name',
            'Registrar Name:'          => 'domain.sponsor',
            'Status:'                  => 'domain.status',
            'Registrant Contact ID:'   => 'owner.handle',
            'Registrant Contact Name:' => 'owner.name',
            'Tech Contact Name:'       => 'tech.name',
            'Tech Contact ID:'         => 'tech.handle',
            'Name Server:'             => 'domain.nserver.',
        ];

        $r = [];
        $r['regrinfo'] = generic_parser_b($data_str['rawdata'], $items, 'ymd');

        $r['regyinfo'] = [
            'referrer'  => 'http://www.nic.ae',
            'registrar' => 'UAENIC',
        ];

        return $r;
    }
}

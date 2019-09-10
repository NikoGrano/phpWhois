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

if (!\defined('__AU_HANDLER__')) {
    \define('__AU_HANDLER__', 1);
}

require_once 'whois.parser.php';

class au_handler
{
    public function parse($data_str, $query)
    {
        $items = [
            'Domain Name:'              => 'domain.name',
            'Last Modified:'            => 'domain.changed',
            'Registrar Name:'           => 'domain.sponsor',
            'Status:'                   => 'domain.status',
            'Domain ROID:'              => 'domain.handle',
            'Registrant:'               => 'owner.organization',
            'Registrant Contact ID:'    => 'owner.handle',
            'Registrant Contact Email:' => 'owner.email',
            'Registrant Contact Name:'  => 'owner.name',
            'Tech Contact Name:'        => 'tech.name',
            'Tech Contact Email:'       => 'tech.email',
            'Tech Contact ID:'          => 'tech.handle',
            'Name Server:'              => 'domain.nserver.',
        ];

        $r = [];
        $r['regrinfo'] = generic_parser_b($data_str['rawdata'], $items);
        $r['regyinfo'] = [
            'referrer'  => 'http://www.aunic.net',
            'registrar' => 'AU-NIC',
        ];

        return $r;
    }
}

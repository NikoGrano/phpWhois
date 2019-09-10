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

if (!\defined('__RU_HANDLER__')) {
    \define('__RU_HANDLER__', 1);
}

require_once 'whois.parser.php';

class ru_handler
{
    public function parse($data_str, $query)
    {
        $items = [
            'domain:'    => 'domain.name',
            'registrar:' => 'domain.sponsor',
            'state:'     => 'domain.status',
            'nserver:'   => 'domain.nserver.',
            'source:'    => 'domain.source',
            'created:'   => 'domain.created',
            'paid-till:' => 'domain.expires',
            'type:'      => 'owner.type',
            'org:'       => 'owner.organization',
            'phone:'     => 'owner.phone',
            'fax-no:'    => 'owner.fax',
            'e-mail:'    => 'owner.email',
        ];

        $r = [];
        $r['regrinfo'] = generic_parser_b($data_str['rawdata'], $items, 'dmy');

        if (empty($r['regrinfo']['domain']['status'])) {
            $r['regrinfo']['registered'] = 'no';
        }

        $r['regyinfo'] = [
            'referrer'  => 'http://www.ripn.net',
            'registrar' => 'RU-CENTER-REG-RIPN',
        ];

        return $r;
    }
}

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

if (!\defined('__LU_HANDLER__')) {
    \define('__LU_HANDLER__', 1);
}

require_once 'whois.parser.php';

class lu_handler
{
    public function parse($data_str, $query)
    {
        $items = [
            'domainname:'  => 'domain.name',
            'domaintype:'  => 'domain.status',
            'nserver:'     => 'domain.nserver.',
            'registered:'  => 'domain.created',
            'source:'      => 'domain.source',
            'ownertype:'   => 'owner.type',
            'org-name:'    => 'owner.organization',
            'org-address:' => 'owner.address.',
            'org-zipcode:' => 'owner.address.pcode',
            'org-city:'    => 'owner.address.city',
            'org-country:' => 'owner.address.country',
            'adm-name:'    => 'admin.name',
            'adm-address:' => 'admin.address.',
            'adm-zipcode:' => 'admin.address.pcode',
            'adm-city:'    => 'admin.address.city',
            'adm-country:' => 'admin.address.country',
            'adm-email:'   => 'admin.email',
            'tec-name:'    => 'tech.name',
            'tec-address:' => 'tech.address.',
            'tec-zipcode:' => 'tech.address.pcode',
            'tec-city:'    => 'tech.address.city',
            'tec-country:' => 'tech.address.country',
            'tec-email:'   => 'tech.email',
            'bil-name:'    => 'billing.name',
            'bil-address:' => 'billing.address.',
            'bil-zipcode:' => 'billing.address.pcode',
            'bil-city:'    => 'billing.address.city',
            'bil-country:' => 'billing.address.country',
            'bil-email:'   => 'billing.email',
        ];

        $r = [];
        $r['regrinfo'] = generic_parser_b($data_str['rawdata'], $items, 'dmy');

        $r['regyinfo'] = [
            'referrer'  => 'http://www.dns.lu',
            'registrar' => 'DNS-LU',
        ];

        return $r;
    }
}

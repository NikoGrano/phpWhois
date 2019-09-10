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

if (!\defined('__CN_HANDLER__')) {
    \define('__CN_HANDLER__', 1);
}

require_once 'whois.parser.php';

class cn_handler
{
    public function parse($data_str, $query)
    {
        $items = [
            'Domain Name:'                 => 'domain.name',
            'Domain Status:'               => 'domain.status.',
            'ROID:'                        => 'domain.handle',
            'Name Server:'                 => 'domain.nserver.',
            'Registration Date:'           => 'domain.created',
            'Expiration Date:'             => 'domain.expires',
            'Sponsoring Registrar:'        => 'domain.sponsor',
            'Registrant Name:'             => 'owner.name',
            'Registrant Organization:'     => 'owner.organization',
            'Registrant Address:'          => 'owner.address.address',
            'Registrant Postal Code:'      => 'owner.address.pcode',
            'Registrant City:'             => 'owner.address.city',
            'Registrant Country Code:'     => 'owner.address.country',
            'Registrant Email:'            => 'owner.email',
            'Registrant Phone Number:'     => 'owner.phone',
            'Registrant Fax:'              => 'owner.fax',
            'Administrative Name:'         => 'admin.name',
            'Administrative Organization:' => 'admin.organization',
            'Administrative Address:'      => 'admin.address.address',
            'Administrative Postal Code:'  => 'admin.address.pcode',
            'Administrative City:'         => 'admin.address.city',
            'Administrative Country Code:' => 'admin.address.country',
            'Administrative Email:'        => 'admin.email',
            'Administrative Phone Number:' => 'admin.phone',
            'Administrative Fax:'          => 'admin.fax',
            'Technical Name:'              => 'tech.name',
            'Technical Organization:'      => 'tech.organization',
            'Technical Address:'           => 'tech.address.address',
            'Technical Postal Code:'       => 'tech.address.pcode',
            'Technical City:'              => 'tech.address.city',
            'tec-country:'                 => 'tech.address.country',
            'Technical Email:'             => 'tech.email',
            'Technical Phone Number:'      => 'tech.phone',
            'Technical Fax:'               => 'tech.fax',
            'Billing Name:'                => 'billing.name',
            'Billing Organization:'        => 'billing.organization',
            'Billing Address:'             => 'billing.address.address',
            'Billing Postal Code:'         => 'billing.address.pcode',
            'Billing City:'                => 'billing.address.city',
            'Billing Country Code:'        => 'billing.address.country',
            'Billing Email:'               => 'billing.email',
            'Billing Phone Number:'        => 'billing.phone',
            'Billing Fax:'                 => 'billing.fax',
        ];

        $r = [];
        $r['regrinfo'] = generic_parser_b($data_str['rawdata'], $items, 'ymd');
        $r['regyinfo'] = [
            'referrer'  => 'http://www.cnnic.net.cn',
            'registrar' => 'China NIC',
        ];

        return $r;
    }
}

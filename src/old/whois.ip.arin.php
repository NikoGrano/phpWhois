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

if (!\defined('__ARIN_HANDLER__')) {
    \define('__ARIN_HANDLER__', 1);
}

require_once 'whois.parser.php';

class arin_handler
{
    public function parse($data_str, $query)
    {
        $items = [
            'OrgName:'        => 'owner.organization',
            'CustName:'       => 'owner.organization',
            'OrgId:'          => 'owner.handle',
            'Address:'        => 'owner.address.street.',
            'City:'           => 'owner.address.city',
            'StateProv:'      => 'owner.address.state',
            'PostalCode:'     => 'owner.address.pcode',
            'Country:'        => 'owner.address.country',
            'NetRange:'       => 'network.inetnum',
            'NetName:'        => 'network.name',
            'NetHandle:'      => 'network.handle',
            'NetType:'        => 'network.status',
            'NameServer:'     => 'network.nserver.',
            'Comment:'        => 'network.desc.',
            'RegDate:'        => 'network.created',
            'Updated:'        => 'network.changed',
            'ASHandle:'       => 'network.handle',
            'ASName:'         => 'network.name',
            'NetHandle:'      => 'network.handle',
            'NetName:'        => 'network.name',
            'TechHandle:'     => 'tech.handle',
            'TechName:'       => 'tech.name',
            'TechPhone:'      => 'tech.phone',
            'TechEmail:'      => 'tech.email',
            'OrgAbuseName:'   => 'abuse.name',
            'OrgAbuseHandle:' => 'abuse.handle',
            'OrgAbusePhone:'  => 'abuse.phone',
            'OrgAbuseEmail:'  => 'abuse.email.',
            'ReferralServer:' => 'rwhois',
        ];

        $r = generic_parser_b($data_str, $items, 'ymd', false, true);

        if (@isset($r['abuse']['email'])) {
            $r['abuse']['email'] = \implode(',', $r['abuse']['email']);
        }

        return ['regrinfo' => $r];
    }
}

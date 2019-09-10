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

if (!\defined('__SCHLUND_HANDLER__')) {
    \define('__SCHLUND_HANDLER__', 1);
}

require_once 'whois.parser.php';

class schlund_handler
{
    public function parse($data_str, $query)
    {
        $items = [
            'created:'                 => 'domain.created',
            'last-changed:'            => 'domain.changed',
            'status:'                  => 'domain.status',
            'registrant-firstname:'    => 'owner.name.first',
            'registrant-lastname:'     => 'owner.name.last',
            'registrant-organization:' => 'owner.organization',
            'registrant-street1:'      => 'owner.address.street.',
            'registrant-street2:'      => 'owner.address.street.',
            'registrant-pcode:'        => 'owner.address.pcode',
            'registrant-city:'         => 'owner.address.city',
            'registrant-ccode:'        => 'owner.address.country',
            'registrant-phone:'        => 'owner.phone',
            'registrant-email:'        => 'owner.email',
            'admin-c-firstname:'       => 'admin.name.first',
            'admin-c-lastname:'        => 'admin.name.last',
            'admin-c-organization:'    => 'admin.organization',
            'admin-c-street1:'         => 'admin.address.street.',
            'admin-c-street2:'         => 'admin.address.street.',
            'admin-c-pcode:'           => 'admin.address.pcode',
            'admin-c-city:'            => 'admin.address.city',
            'admin-c-ccode:'           => 'admin.address.country',
            'admin-c-phone:'           => 'admin.phone',
            'admin-c-email:'           => 'admin.email',
            'tech-c-firstname:'        => 'tech.name.first',
            'tech-c-lastname:'         => 'tech.name.last',
            'tech-c-organization:'     => 'tech.organization',
            'tech-c-street1:'          => 'tech.address.street.',
            'tech-c-street2:'          => 'tech.address.street.',
            'tech-c-pcode:'            => 'tech.address.pcode',
            'tech-c-city:'             => 'tech.address.city',
            'tech-c-ccode:'            => 'tech.address.country',
            'tech-c-phone:'            => 'tech.phone',
            'tech-c-email:'            => 'tech.email',
            'bill-c-firstname:'        => 'billing.name.first',
            'bill-c-lastname:'         => 'billing.name.last',
            'bill-c-organization:'     => 'billing.organization',
            'bill-c-street1:'          => 'billing.address.street.',
            'bill-c-street2:'          => 'billing.address.street.',
            'bill-c-pcode:'            => 'billing.address.pcode',
            'bill-c-city:'             => 'billing.address.city',
            'bill-c-ccode:'            => 'billing.address.country',
            'bill-c-phone:'            => 'billing.phone',
            'bill-c-email:'            => 'billing.email',
        ];

        return generic_parser_b($data_str, $items);
    }
}

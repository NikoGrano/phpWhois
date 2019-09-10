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

if (!\defined('__INTERDOMAIN_HANDLER__')) {
    \define('__INTERDOMAIN_HANDLER__', 1);
}

require_once 'whois.parser.php';

class interdomain_handler
{
    public function parse($data_str, $query)
    {
        $items = [
            'Domain Name................' => 'domain.name',
            'Creation Date............'   => 'domain.created',
            'Expiry Date..............'   => 'domain.expires',
            'Last Update Date.........'   => 'domain.changed',
            'Name Server.............'    => 'domain.nserver.',
            'Organization Name........'   => 'owner.name',
            'Organization Org.........'   => 'owner.organization',
            'Organization Street......'   => 'owner.address.street',
            'Organization City........'   => 'owner.address.city',
            'Organization State.......'   => 'owner.address.state',
            'Organization PC..........'   => 'owner.address.pcode',
            'Organization Country.....'   => 'owner.address.country',
            'Organization Phone.......'   => 'owner.phone',
            'Organization e-mail......'   => 'owner.email',
            'Organization Contact Id....' => 'owner.handle',
            'Administrative Contact Id..' => 'admin.handle',
            'Administrative Name......'   => 'admin.name',
            'Administrative Org.......'   => 'admin.organization',
            'Administrative Street....'   => 'admin.address.street',
            'Administrative City......'   => 'admin.address.city',
            'Administrative State.....'   => 'admin.address.state',
            'Administrative PC........'   => 'admin.address.pcode',
            'Administrative Country...'   => 'admin.address.country',
            'Administrative Phone.....'   => 'admin.phone',
            'Administrative e-mail....'   => 'admin.email',
            'Administrative Fax.......'   => 'admin.fax',
            'Technical Contact Id.......' => 'tech.handle',
            'Technical Name...........'   => 'tech.name',
            'Technical Org............'   => 'tech.organization',
            'Technical Street.........'   => 'tech.address.street',
            'Technical City...........'   => 'tech.address.city',
            'Technical State..........'   => 'tech.address.state',
            'Technical PC.............'   => 'tech.address.pcode',
            'Technical Country........'   => 'tech.address.country',
            'Technical Phone..........'   => 'tech.phone',
            'Technical e-mail.........'   => 'tech.email',
            'Technical Fax............'   => 'tech.fax',
        ];

        return generic_parser_b($data_str, $items, 'dmy');
    }
}

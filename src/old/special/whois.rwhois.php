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

if (!\defined('__RWHOIS_HANDLER__')) {
    \define('__RWHOIS_HANDLER__', 1);
}

require_once 'whois.parser.php';

class rwhois_handler
{
    public function parse($data_str, $query)
    {
        $items = [
            'network:Organization-Name:'    => 'owner.name',
            'network:Organization;I:'       => 'owner.organization',
            'network:Organization-City:'    => 'owner.address.city',
            'network:Organization-Zip:'     => 'owner.address.pcode',
            'network:Organization-Country:' => 'owner.address.country',
            'network:IP-Network-Block:'     => 'network.inetnum',
            'network:IP-Network:'           => 'network.inetnum',
            'network:Network-Name:'         => 'network.name',
            'network:ID:'                   => 'network.handle',
            'network:Created:'              => 'network.created',
            'network:Updated:'              => 'network.changed',
            'network:Tech-Contact;I:'       => 'tech.email',
            'network:Admin-Contact;I:'      => 'admin.email',
        ];

        $res = generic_parser_b($data_str, $items, 'Ymd', false);
        if (isset($res['disclaimer'])) {
            unset($res['disclaimer']);
        }

        return ['regrinfo' => $res];
    }
}

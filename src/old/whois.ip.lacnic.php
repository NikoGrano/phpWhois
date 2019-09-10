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

require_once 'whois.parser.php';

if (!\defined('__LACNIC_HANDLER__')) {
    \define('__LACNIC_HANDLER__', 1);
}

class lacnic_handler
{
    public function parse($data_str, $query)
    {
        $translate = [
            'fax-no'     => 'fax',
            'e-mail'     => 'email',
            'nic-hdl-br' => 'handle',
            'nic-hdl'    => 'handle',
            'person'     => 'name',
            'netname'    => 'name',
            'descr'      => 'desc',
            'country'    => 'address.country',
        ];

        $contacts = [
            'owner-c' => 'owner',
            'tech-c'  => 'tech',
            'abuse-c' => 'abuse',
            'admin-c' => 'admin',
        ];

        $r = generic_parser_a($data_str, $translate, $contacts, 'network');

        unset($r['network']['owner'], $r['network']['ownerid'], $r['network']['responsible'], $r['network']['address'], $r['network']['phone'], $r['network']['aut-num'], $r['network']['nsstat'], $r['network']['nslastaa'], $r['network']['inetrev']);

        if (!empty($r['network']['aut-num'])) {
            $r['network']['handle'] = $r['network']['aut-num'];
        }

        if (isset($r['network']['nserver'])) {
            $r['network']['nserver'] = \array_unique($r['network']['nserver']);
        }

        $r = ['regrinfo' => $r];
        $r['regyinfo']['type'] = 'ip';
        $r['regyinfo']['registrar'] = 'Latin American and Caribbean IP address Regional Registry';

        return $r;
    }
}

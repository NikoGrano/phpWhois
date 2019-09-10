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

if (!\defined('__FR_HANDLER__')) {
    \define('__FR_HANDLER__', 1);
}

require_once 'whois.parser.php';

class fr_handler
{
    public function parse($data_str, $query)
    {
        $translate = [
            'fax-no'      => 'fax',
            'e-mail'      => 'email',
            'nic-hdl'     => 'handle',
            'ns-list'     => 'handle',
            'person'      => 'name',
            'address'     => 'address.',
            'descr'       => 'desc',
            'anniversary' => '',
            'domain'      => '',
            'last-update' => 'changed',
            'registered'  => 'created',
            'country'     => 'address.country',
            'registrar'   => 'sponsor',
            'role'        => 'organization',
        ];

        $contacts = [
            'admin-c'  => 'admin',
            'tech-c'   => 'tech',
            'zone-c'   => 'zone',
            'holder-c' => 'owner',
            'nsl-id'   => 'nserver',
        ];

        $reg = generic_parser_a($data_str['rawdata'], $translate, $contacts, 'domain', 'dmY');

        if (isset($reg['nserver'])) {
            $reg['domain'] = \array_merge($reg['domain'], $reg['nserver']);
            unset($reg['nserver']);
        }

        $r = [];
        $r['regrinfo'] = $reg;
        $r['regyinfo'] = [
            'referrer'  => 'http://www.nic.fr',
            'registrar' => 'AFNIC',
        ];

        return $r;
    }
}

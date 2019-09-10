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

if (!\defined('__IS_HANDLER__')) {
    \define('__IS_HANDLER__', 1);
}

require_once 'whois.parser.php';

class is_handler
{
    public function parse($data_str, $query)
    {
        $translate = [
            'fax-no'  => 'fax',
            'e-mail'  => 'email',
            'nic-hdl' => 'handle',
            'person'  => 'name',
        ];

        $contacts = [
            'owner-c'   => 'owner',
            'admin-c'   => 'admin',
            'tech-c'    => 'tech',
            'billing-c' => 'billing',
            'zone-c'    => 'zone',
        ];

        $reg = generic_parser_a($data_str['rawdata'], $translate, $contacts, 'domain', 'mdy');

        if (isset($reg['domain']['descr'])) {
            $reg['owner']['name'] = \array_shift($reg['domain']['descr']);
            $reg['owner']['address'] = $reg['domain']['descr'];
            unset($reg['domain']['descr']);
        }

        $r = [];
        $r['regrinfo'] = $reg;
        $r['regyinfo'] = [
            'referrer'  => 'http://www.isnic.is',
            'registrar' => 'ISNIC',
        ];

        return $r;
    }
}

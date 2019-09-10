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

// Define the handler flag.
if (!\defined('__IR_HANDLER__')) {
    \define('__IR_HANDLER__', 1);
}

// Loadup the parser.
require_once 'whois.parser.php';

/**
 * IR Domain names lookup handler class.
 */
class ir_handler
{
    public function parse($data_str, $query)
    {
        $translate = [
            'nic-hdl' => 'handle',
            'org'     => 'organization',
            'e-mail'  => 'email',
            'person'  => 'name',
            'fax-no'  => 'fax',
            'domain'  => 'name',
        ];

        $contacts = [
            'admin-c'  => 'admin',
            'tech-c'   => 'tech',
            'holder-c' => 'owner',
        ];

        $reg = generic_parser_a($data_str['rawdata'], $translate, $contacts, 'domain', 'Ymd');

        $r = [];
        $r['regrinfo'] = $reg;
        $r['regyinfo'] = [
            'referrer'  => 'http://whois.nic.ir/',
            'registrar' => 'NIC-IR',
        ];

        return $r;
    }
}

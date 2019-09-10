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

if (!\defined('__CZ_HANDLER__')) {
    \define('__CZ_HANDLER__', 1);
}

require_once 'whois.parser.php';

class cz_handler
{
    public function parse($data_str, $query)
    {
        $translate = [
            'expire'     => 'expires',
            'registered' => 'created',
            'nserver'    => 'nserver',
            'domain'     => 'name',
            'contact'    => 'handle',
            'reg-c'      => '',
            'descr'      => 'desc',
            'e-mail'     => 'email',
            'person'     => 'name',
            'org'        => 'organization',
            'fax-no'     => 'fax',
        ];

        $contacts = [
            'admin-c'    => 'admin',
            'tech-c'     => 'tech',
            'bill-c'     => 'billing',
            'registrant' => 'owner',
        ];

        $r = [];
        $r['regrinfo'] = generic_parser_a($data_str['rawdata'], $translate, $contacts, 'domain', 'dmy');

        $r['regyinfo'] = [
            'referrer'  => 'http://www.nic.cz',
            'registrar' => 'CZ-NIC',
        ];

        if ('Your connection limit exceeded. Please slow down and try again later.' === $data_str['rawdata'][0]) {
            $r['regrinfo']['registered'] = 'unknown';
        }

        return $r;
    }
}

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

if (!\defined('__SI_HANDLER__')) {
    \define('__SI_HANDLER__', 1);
}

require_once 'whois.parser.php';

class si_handler
{
    public function parse($data_str, $query)
    {
        $translate = [
            'nic-hdl'    => 'handle',
            'nameserver' => 'nserver',
        ];

        $contacts = [
            'registrant' => 'owner',
            'tech-c'     => 'tech',
        ];

        $r = [];
        $r['regrinfo'] = generic_parser_a($data_str['rawdata'], $translate, $contacts, 'domain', 'Ymd');
        $r['regyinfo'] = [
            'referrer'  => 'http://www.arnes.si',
            'registrar' => 'ARNES',
        ];

        return $r;
    }
}

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

if (!\defined('__IN_HANDLER__')) {
    \define('__IN_HANDLER__', 1);
}

require_once 'whois.parser.php';

class in_handler
{
    public function parse($data_str, $query)
    {
        $r = [];
        $r['regrinfo'] = generic_parser_b($data_str['rawdata']);
        $r['regyinfo'] = [
            'referrer'  => 'http://whois.registry.in',
            'registrar' => 'INRegistry',
        ];

        return $r;
    }
}

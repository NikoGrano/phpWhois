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

require_once 'whois.parser.php';

if (!\defined('__PRO_HANDLER__')) {
    \define('__PRO_HANDLER__', 1);
}

class pro_handler
{
    public function parse($data, $query)
    {
        $r = [];
        $r['regrinfo'] = generic_parser_b($data['rawdata']);
        $r['regyinfo']['referrer'] = 'http://www.registrypro.pro';
        $r['regyinfo']['registrar'] = 'RegistryPRO';

        return $r;
    }
}

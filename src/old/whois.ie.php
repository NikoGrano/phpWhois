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

if (!\defined('__IE_HANDLER__')) {
    \define('__IE_HANDLER__', 1);
}

require_once 'whois.parser.php';

class ie_handler
{
    public function parse($data_str, $query)
    {
        $translate = [
            'nic-hdl' => 'handle',
            'person'  => 'name',
            'renewal' => 'expires',
        ];

        $contacts = [
            'admin-c' => 'admin',
            'tech-c'  => 'tech',
        ];

        $reg = generic_parser_a($data_str['rawdata'], $translate, $contacts, 'domain', 'Ymd');

        if (isset($reg['domain']['descr'])) {
            $reg['owner']['organization'] = $reg['domain']['descr'][0];
            unset($reg['domain']['descr']);
        }

        $r = [];
        $r['regrinfo'] = $reg;
        $r['regyinfo'] = [
            'referrer'  => 'http://www.domainregistry.ie',
            'registrar' => 'IE Domain Registry',
        ];

        return $r;
    }
}

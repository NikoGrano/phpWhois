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

if (!\defined('__CL_HANDLER__')) {
    \define('__CL_HANDLER__', 1);
}

require_once 'whois.parser.php';

class cl_handler
{
    public function parse($data_str, $query)
    {
        $items = [
            'admin'          => '(Administrative Contact)',
            'tech'           => 'Contacto Técnico (Technical Contact):',
            'domain.nserver' => 'Servidores de nombre (Domain servers):',
            'domain.changed' => '(Database last updated on):',
        ];

        $trans = [
            'organización:' => 'organization',
            'nombre      :' => 'name', ];

        $r = [];
        $r['regrinfo'] = easy_parser($data_str['rawdata'], $items, 'd-m-y', $trans);
        $r['regyinfo'] = [
            'referrer'  => 'http://www.nic.cl',
            'registrar' => 'NIC Chile',
        ];

        return $r;
    }
}

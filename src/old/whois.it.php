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

/*
 * @todo BUG
 * - nserver -> array
 * - ContactID in address
 */
if (!\defined('__IT_HANDLER__')) {
    \define('__IT_HANDLER__', 1);
}

require_once 'whois.parser.php';

class it_handler
{
    public function parse($data_str, $query)
    {
        $items = [
            'domain.name'    => 'Domain:',
            'domain.nserver' => 'Nameservers',
            'domain.status'  => 'Status:',
            'domain.expires' => 'Expire Date:',
            'owner'          => 'Registrant',
            'admin'          => 'Admin Contact',
            'tech'           => 'Technical Contacts',
            'registrar'      => 'Registrar',
        ];

        $extra = [
            'address:'      => 'address.',
            'contactid:'    => 'handle',
            'organization:' => 'organization',
            'created:'      => 'created',
            'last update:'  => 'changed',
            'web:'          => 'web',
        ];

        $r = [];
        $r['regrinfo'] = easy_parser($data_str['rawdata'], $items, 'ymd', $extra);

        if (isset($r['regrinfo']['registrar'])) {
            $r['regrinfo']['domain']['registrar'] = $r['regrinfo']['registrar'];
            unset($r['regrinfo']['registrar']);
        }

        $r['regyinfo'] = [
            'registrar' => 'IT-Nic',
            'referrer'  => 'http://www.nic.it/',
        ];

        return $r;
    }
}

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

if (!\defined('__BR_HANDLER__')) {
    \define('__BR_HANDLER__', 1);
}

class br_handler
{
    public function parse($data_str, $query)
    {
        $translate = [
            'fax-no'     => 'fax',
            'e-mail'     => 'email',
            'nic-hdl-br' => 'handle',
            'person'     => 'name',
            'netname'    => 'name',
            'domain'     => 'name',
            'updated'    => '',
        ];

        $contacts = [
            'owner-c'   => 'owner',
            'tech-c'    => 'tech',
            'admin-c'   => 'admin',
            'billing-c' => 'billing',
        ];

        $r = generic_parser_a($data_str['rawdata'], $translate, $contacts, 'domain', 'Ymd');

        if (\in_array('Permission denied.', $r['disclaimer'], true)) {
            $r['registered'] = 'unknown';

            return $r;
        }

        if (isset($r['domain']['nsstat'])) {
            unset($r['domain']['nsstat']);
        }
        if (isset($r['domain']['nslastaa'])) {
            unset($r['domain']['nslastaa']);
        }

        if (isset($r['domain']['owner'])) {
            $r['owner']['organization'] = $r['domain']['owner'];
            unset($r['domain']['owner']);
        }

        if (isset($r['domain']['responsible'])) {
            unset($r['domain']['responsible']);
        }
        if (isset($r['domain']['address'])) {
            unset($r['domain']['address']);
        }
        if (isset($r['domain']['phone'])) {
            unset($r['domain']['phone']);
        }

        $a = [];
        $a['regrinfo'] = $r;
        $a['regyinfo'] = [
            'registrar' => 'BR-NIC',
            'referrer'  => 'http://www.nic.br',
        ];

        return $a;
    }
}

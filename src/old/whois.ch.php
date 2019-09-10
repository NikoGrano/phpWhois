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

require_once 'whois.parser.php';

if (!\defined('__CH_HANDLER__')) {
    \define('__CH_HANDLER__', 1);
}

class ch_handler
{
    public function parse($data_str, $query)
    {
        $items = [
            'owner'          => 'Holder of domain name:',
            'domain.name'    => 'Domain name:',
            'domain.created' => 'Date of last registration:',
            'domain.changed' => 'Date of last modification:',
            'tech'           => 'Technical contact:',
            'domain.nserver' => 'Name servers:',
            'domain.dnssec'  => 'DNSSEC:',
        ];

        $trans = [
            'contractual language:' => 'language',
        ];

        $r = [];
        $r['regrinfo'] = get_blocks($data_str['rawdata'], $items);

        if (!empty($r['regrinfo']['domain']['name'])) {
            $r['regrinfo'] = get_contacts($r['regrinfo'], $trans);

            $r['regrinfo']['domain']['name'] = $r['regrinfo']['domain']['name'][0];

            if (isset($r['regrinfo']['domain']['changed'][0])) {
                $r['regrinfo']['domain']['changed'] = get_date($r['regrinfo']['domain']['changed'][0], 'dmy');
            }

            if (isset($r['regrinfo']['domain']['created'][0])) {
                $r['regrinfo']['domain']['created'] = get_date($r['regrinfo']['domain']['created'][0], 'dmy');
            }

            $r['regrinfo']['registered'] = 'yes';
        } else {
            $r = '';
            $r['regrinfo']['registered'] = 'no';
        }

        $r['regyinfo'] = [
            'referrer'  => 'http://www.nic.ch',
            'registrar' => 'SWITCH Domain Name Registration',
        ];

        return $r;
    }
}

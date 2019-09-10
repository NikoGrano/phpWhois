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

if (!\defined('__DE_HANDLER__')) {
    \define('__DE_HANDLER__', 1);
}

require_once 'whois.parser.php';

class de_handler
{
    public function parse($data_str, $query)
    {
        $items = [
            'domain.name'      => 'Domain:',
            'domain.nserver.'  => 'Nserver:',
            'domain.nserver.#' => 'Nsentry:',
            'domain.status'    => 'Status:',
            'domain.changed'   => 'Changed:',
            'domain.desc.'     => 'Descr:',
            'owner'            => '[Holder]',
            'admin'            => '[Admin-C]',
            'tech'             => '[Tech-C]',
            'zone'             => '[Zone-C]',
        ];

        $extra = [
            'city:'        => 'address.city',
            'postalcode:'  => 'address.pcode',
            'countrycode:' => 'address.country',
            'remarks:'     => '',
            'sip:'         => 'sip',
            'type:'        => '',
        ];

        $r = [];

        $r['regrinfo'] = easy_parser($data_str['rawdata'], $items, 'ymd', $extra);

        $r['regyinfo'] = [
            'registrar' => 'DENIC eG',
            'referrer'  => 'http://www.denic.de/',
        ];

        if (!isset($r['regrinfo']['domain']['status']) || 'free' === $r['regrinfo']['domain']['status']) {
            $r['regrinfo']['registered'] = 'no';
        } else {
            $r['regrinfo']['domain']['changed'] = \mb_substr($r['regrinfo']['domain']['changed'], 0, 10);
            $r['regrinfo']['registered'] = 'yes';
        }

        return $r;
    }
}

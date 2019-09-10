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

if (!\defined('__AT_HANDLER__')) {
    \define('__AT_HANDLER__', 1);
}

require_once 'whois.parser.php';

class at_handler
{
    public function parse($data_str, $query)
    {
        $translate = [
            'fax-no'         => 'fax',
            'e-mail'         => 'email',
            'nic-hdl'        => 'handle',
            'person'         => 'name',
            'personname'     => 'name',
            'street address' => 'address.street',
            'city'           => 'address.city',
            'postal code'    => 'address.pcode',
            'country'        => 'address.country',
        ];

        $contacts = [
            'registrant' => 'owner',
            'admin-c'    => 'admin',
            'tech-c'     => 'tech',
            'billing-c'  => 'billing',
            'zone-c'     => 'zone',
        ];

        $reg = generic_parser_a($data_str['rawdata'], $translate, $contacts, 'domain', 'Ymd');

        if (isset($reg['domain']['remarks'])) {
            unset($reg['domain']['remarks']);
        }

        if (isset($reg['domain']['descr'])) {
            while (list($key, $val) = \each($reg['domain']['descr'])) {
                $v = \trim(\mb_substr(\mb_strstr($val, ':'), 1));
                if (\mb_strstr($val, '[organization]:')) {
                    $reg['owner']['organization'] = $v;
                    continue;
                }
                if (\mb_strstr($val, '[phone]:')) {
                    $reg['owner']['phone'] = $v;
                    continue;
                }
                if (\mb_strstr($val, '[fax-no]:')) {
                    $reg['owner']['fax'] = $v;
                    continue;
                }
                if (\mb_strstr($val, '[e-mail]:')) {
                    $reg['owner']['email'] = $v;
                    continue;
                }

                $reg['owner']['address'][$key] = $v;
            }

            if (isset($reg['domain']['descr'])) {
                unset($reg['domain']['descr']);
            }
        }

        $r = [];
        $r['regrinfo'] = $reg;
        $r['regyinfo'] = [
            'referrer'  => 'http://www.nic.at',
            'registrar' => 'NIC-AT',
        ];

        return $r;
    }
}

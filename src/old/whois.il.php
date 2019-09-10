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

if (!\defined('__IL_HANDLER__')) {
    \define('__IL_HANDLER__', 1);
}

require_once 'whois.parser.php';

class il_handler
{
    public function parse($data_str, $query)
    {
        $translate = [
            'fax-no'     => 'fax',
            'e-mail'     => 'email',
            'nic-hdl'    => 'handle',
            'person'     => 'name',
            'personname' => 'name',
            'address'    => 'address', /* ,
                  'address' => 'address.city',
                  'address' => 'address.pcode',
                  'address' => 'address.country' */
        ];

        $contacts = [
            'registrant' => 'owner',
            'admin-c'    => 'admin',
            'tech-c'     => 'tech',
            'billing-c'  => 'billing',
            'zone-c'     => 'zone',
        ];
        //unset($data_str['rawdata'][19]);
        \array_splice($data_str['rawdata'], 16, 1);
        \array_splice($data_str['rawdata'], 18, 1);
        //print_r($data_str['rawdata']);
        //die;
        $reg = generic_parser_a($data_str['rawdata'], $translate, $contacts, 'domain', 'Ymd');

        if (isset($reg['domain']['remarks'])) {
            unset($reg['domain']['remarks']);
        }

        if (isset($reg['domain']['descr:'])) {
            while (list($key, $val) = \each($reg['domain']['descr:'])) {
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

            if (isset($reg['domain']['descr:'])) {
                unset($reg['domain']['descr:']);
            }
        }

        $r = [];
        $r['regrinfo'] = $reg;
        $r['regyinfo'] = [
            'referrer'  => 'http://www.isoc.org.il/',
            'registrar' => 'ISOC-IL',
        ];

        return $r;
    }
}

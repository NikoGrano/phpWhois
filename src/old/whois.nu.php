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

if (!\defined('__NU_HANDLER__')) {
    \define('__NU_HANDLER__', 1);
}

require_once 'whois.parser.php';

class nu_handler
{
    public function parse($data_str, $query)
    {
        $items = [
            'name'    => 'Domain Name (UTF-8):',
            'created' => 'Record created on',
            'expires' => 'Record expires on',
            'changed' => 'Record last updated on',
            'status'  => 'Record status:',
            'handle'  => 'Record ID:',
        ];

        $r = [];
        while (list($key, $val) = \each($data_str['rawdata'])) {
            $val = \trim($val);

            if ('' !== $val) {
                if ('Domain servers in listed order:' === $val) {
                    while (list($key, $val) = \each($data_str['rawdata'])) {
                        $val = \trim($val);
                        if ('' === $val) {
                            break;
                        }
                        $r['regrinfo']['domain']['nserver'][] = $val;
                    }
                    break;
                }

                \reset($items);

                while (list($field, $match) = \each($items)) {
                    if (\mb_strstr($val, $match)) {
                        $r['regrinfo']['domain'][$field] = \trim(\mb_substr($val, \mb_strlen($match)));
                        break;
                    }
                }
            }
        }

        if (isset($r['regrinfo']['domain'])) {
            $r['regrinfo']['registered'] = 'yes';
        } else {
            $r['regrinfo']['registered'] = 'no';
        }

        $r['regyinfo'] = [
            'whois'     => 'whois.nic.nu',
            'referrer'  => 'http://www.nunames.nu',
            'registrar' => '.NU Domain, Ltd',
        ];

        format_dates($r, 'dmy');

        return $r;
    }
}

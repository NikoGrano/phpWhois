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

if (!\defined('__FASTDOMAIN_HANDLER__')) {
    \define('__FASTDOMAIN_HANDLER__', 1);
}

require_once 'whois.parser.php';

class fastdomain_handler
{
    public function parse($data_str, $query)
    {
        $items = [
            'owner'           => 'Registrant Info:',
            'admin'           => 'Administrative Info:',
            'tech'            => 'Technical Info:',
            'domain.name'     => 'Domain Name:',
            'domain.sponsor'  => 'Provider Name....:',
            'domain.referrer' => 'Provider Homepage:',
            'domain.nserver'  => 'Domain servers in listed order:',
            'domain.created'  => 'Created on..............:',
            'domain.expires'  => 'Expires on..............:',
            'domain.changed'  => 'Last modified on........:',
            'domain.status'   => 'Status:',
        ];

        while (list($key, $val) = \each($data_str)) {
            $faststr = \mb_strpos($val, ' (FAST-');
            if ($faststr) {
                $data_str[$key] = \mb_substr($val, 0, $faststr);
            }
        }

        $r = easy_parser($data_str, $items, 'dmy', [], false, true);

        if (isset($r['domain']['sponsor']) && \is_array($r['domain']['sponsor'])) {
            $r['domain']['sponsor'] = $r['domain']['sponsor'][0];
        }

        if (isset($r['domain']['nserver'])) {
            \reset($r['domain']['nserver']);

            while (list($key, $val) = \each($r['domain']['nserver'])) {
                if ('=-=-=-=' === $val) {
                    unset($r['domain']['nserver'][$key]);
                }
            }
        }

        return $r;
    }
}

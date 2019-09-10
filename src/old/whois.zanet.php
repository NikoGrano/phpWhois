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

if (!\defined('__ZANET_HANDLER__')) {
    \define('__ZANET_HANDLER__', 1);
}

require_once 'whois.parser.php';

class zanet_handler
{
    public function parse($data_str, $query)
    {
        $items = [
            'domain.name'    => 'Domain Name            : ',
            'domain.created' => 'Record Created         :',
            'domain.changed' => 'Record	Last Updated    :',
            'owner.name'     => 'Registered for         :',
            'admin'          => 'Administrative Contact :',
            'tech'           => 'Technical Contact      :',
            'domain.nserver' => 'Domain Name Servers listed in order:',
            'registered'     => 'No such domain: ',
            ''               => 'The ZA NiC whois',
        ];

        // Arrange contacts ...

        $rawdata = [];

        while (list($key, $line) = \each($data_str['rawdata'])) {
            if (false !== \mb_strpos($line, ' Contact ')) {
                $pos = \mb_strpos($line, ':');

                if (false !== $pos) {
                    $rawdata[] = \mb_substr($line, 0, $pos + 1);
                    $rawdata[] = \trim(\mb_substr($line, $pos + 1));
                    continue;
                }
            }
            $rawdata[] = $line;
        }

        $r = [];
        $r['regrinfo'] = get_blocks($rawdata, $items);

        if (isset($r['regrinfo']['registered'])) {
            $r['regrinfo']['registered'] = 'no';
        } else {
            if (isset($r['regrinfo']['admin'])) {
                $r['regrinfo']['admin'] = get_contact($r['regrinfo']['admin']);
            }

            if (isset($r['regrinfo']['tech'])) {
                $r['regrinfo']['tech'] = get_contact($r['regrinfo']['tech']);
            }
        }

        $r['regyinfo']['referrer'] = 'http://www.za.net/'; // or http://www.za.org
        $r['regyinfo']['registrar'] = 'ZA NiC';
        format_dates($r, 'xmdxxy');

        return $r;
    }
}

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

if (!\defined('__UK_HANDLER__')) {
    \define('__UK_HANDLER__', 1);
}

require_once 'whois.parser.php';

class uk_handler
{
    public function parse($data_str, $query)
    {
        $items = [
            'owner.organization' => 'Registrant:',
            'owner.address'      => "Registrant's address:",
            'owner.type'         => 'Registrant type:',
            'domain.created'     => 'Registered on:',
            'domain.changed'     => 'Last updated:',
            'domain.expires'     => 'Renewal date:',
            'domain.nserver'     => 'Name servers:',
            'domain.sponsor'     => 'Registrar:',
            'domain.status'      => 'Registration status:',
            'domain.dnssec'      => 'DNSSEC:',
            ''                   => 'WHOIS lookup made at',
            'disclaimer'         => '--',
        ];

        $r = [];
        $r['regrinfo'] = get_blocks($data_str['rawdata'], $items);

        if (isset($r['regrinfo']['owner'])) {
            $r['regrinfo']['owner']['organization'] = $r['regrinfo']['owner']['organization'][0];
            $r['regrinfo']['domain']['sponsor'] = $r['regrinfo']['domain']['sponsor'][0];
            $r['regrinfo']['registered'] = 'yes';

            $r = format_dates($r, 'dmy');
        } else {
            if (\mb_strpos($data_str['rawdata'][1], 'Error for ')) {
                $r['regrinfo']['registered'] = 'yes';
                $r['regrinfo']['domain']['status'] = 'invalid';
            } else {
                $r['regrinfo']['registered'] = 'no';
            }
        }

        $r['regyinfo'] = [
            'referrer'  => 'http://www.nominet.org.uk',
            'registrar' => 'Nominet UK',
        ];

        return $r;
    }
}

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

if (!\defined('__FM_HANDLER__')) {
    \define('__FM_HANDLER__', 1);
}

require_once 'whois.parser.php';

class fm_handler
{
    public function parse($data, $query)
    {
        $items = [
            'owner'          => 'Registrant',
            'admin'          => 'Admin',
            'tech'           => 'Technical',
            'billing'        => 'Billing',
            'domain.nserver' => 'Name Servers:',
            'domain.created' => 'Created:',
            'domain.expires' => 'Expires:',
            'domain.changed' => 'Modified:',
            'domain.status'  => 'Status:',
            'domain.sponsor' => 'Registrar Name:',
        ];

        $r = [];
        $r['regrinfo'] = get_blocks($data['rawdata'], $items);

        $items = [
            'phone number:'  => 'phone',
            'email address:' => 'email',
            'fax number:'    => 'fax',
            'organisation:'  => 'organization',
        ];

        if (!empty($r['regrinfo']['domain']['created'])) {
            $r['regrinfo'] = get_contacts($r['regrinfo'], $items);

            if (\count($r['regrinfo']['billing']['address']) > 4) {
                $r['regrinfo']['billing']['address'] = \array_slice($r['regrinfo']['billing']['address'], 0, 4);
            }

            $r['regrinfo']['registered'] = 'yes';
            format_dates($r['regrinfo']['domain'], 'dmY');
        } else {
            $r = '';
            $r['regrinfo']['registered'] = 'no';
        }

        $r['regyinfo']['referrer'] = 'http://www.dot.dm';
        $r['regyinfo']['registrar'] = 'dotFM';

        return $r;
    }
}

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

if (!\defined('__CA_HANDLER__')) {
    \define('__CA_HANDLER__', 1);
}

require_once 'whois.parser.php';

class ca_handler
{
    public function parse($data_str, $query)
    {
        $items = [
            'owner'          => 'Registrant:',
            'admin'          => 'Administrative contact:',
            'tech'           => 'Technical contact:',
            'domain.sponsor' => 'Registrar:',
            'domain.nserver' => 'Name servers:',
            'domain.status'  => 'Domain status:',
            'domain.created' => 'Creation date:',
            'domain.expires' => 'Expiry date:',
            'domain.changed' => 'Updated date:',
        ];

        $extra = [
            'postal address:' => 'address.0',
            'job title:'      => '',
            'number:'         => 'handle',
            'description:'    => 'organization',
        ];

        $r = [];
        $r['regrinfo'] = easy_parser($data_str['rawdata'], $items, 'ymd', $extra);

        if (!empty($r['regrinfo']['domain']['sponsor'])) {
            list($v, $reg) = \explode(':', $r['regrinfo']['domain']['sponsor'][0]);
            $r['regrinfo']['domain']['sponsor'] = \trim($reg);
        }

        if (empty($r['regrinfo']['domain']['status']) || 'available' === $r['regrinfo']['domain']['status']) {
            $r['regrinfo']['registered'] = 'no';
        } else {
            $r['regrinfo']['registered'] = 'yes';
        }

        $r['regyinfo'] = [
            'registrar' => 'CIRA',
            'referrer'  => 'http://www.cira.ca/',
        ];

        return $r;
    }
}

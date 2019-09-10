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

if (!\defined('__EU_HANDLER__')) {
    \define('__EU_HANDLER__', 1);
}

class eu_handler
{
    public function parse($data, $query)
    {
        $items = [
            'domain.name'      => 'Domain:',
            'domain.status'    => 'Status:',
            'domain.nserver'   => 'Name servers:',
            'domain.created'   => 'Registered:',
            'domain.registrar' => 'Registrar:',
            'tech'             => 'Registrar Technical Contacts:',
            'owner'            => 'Registrant:',
            ''                 => 'Please visit',
        ];

        $extra = [
            'organisation:' => 'organization',
            'website:'      => 'url',
        ];

        $r = [];
        $r['regrinfo'] = get_blocks($data['rawdata'], $items);

        if (!empty($r['regrinfo']['domain']['status'])) {
            switch ($r['regrinfo']['domain']['status']) {
                case 'FREE':
                case 'AVAILABLE':
                    $r['regrinfo']['registered'] = 'no';
                    break;

                case 'APPLICATION PENDING':
                    $r['regrinfo']['registered'] = 'pending';
                    break;

                default:
                    $r['regrinfo']['registered'] = 'unknown';
            }
        } else {
            $r['regrinfo']['registered'] = 'yes';
        }

        if (isset($r['regrinfo']['tech'])) {
            $r['regrinfo']['tech'] = get_contact($r['regrinfo']['tech'], $extra);
        }

        if (isset($r['regrinfo']['domain']['registrar'])) {
            $r['regrinfo']['domain']['registrar'] = get_contact($r['regrinfo']['domain']['registrar'], $extra);
        }

        $r['regyinfo']['referrer'] = 'http://www.eurid.eu';
        $r['regyinfo']['registrar'] = 'EURID';

        return $r;
    }
}

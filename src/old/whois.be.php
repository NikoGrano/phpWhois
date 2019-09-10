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

require_once 'whois.parser.php';

if (!\defined('__BE_HANDLER__')) {
    \define('__BE_HANDLER__', 1);
}

class be_handler
{
    public function parse($data, $query)
    {
        $items = [
            'domain.name'    => 'Domain:',
            'domain.status'  => 'Status:',
            'domain.nserver' => 'Nameservers:',
            'domain.created' => 'Registered:',
            'owner'          => 'Licensee:',
            'admin'          => 'Onsite Contacts:',
            'tech'           => 'Registrar Technical Contacts:',
            'agent'          => 'Registrar:',
            'agent.uri'      => 'Website:',
        ];

        $trans = [
            'company name2:' => '',
        ];

        $r = [];

        $r['regrinfo'] = get_blocks($data['rawdata'], $items);

        if ('REGISTERED' === $r['regrinfo']['domain']['status']) {
            $r['regrinfo']['registered'] = 'yes';
            $r['regrinfo'] = get_contacts($r['regrinfo'], $trans);

            if (isset($r['regrinfo']['agent'])) {
                $sponsor = get_contact($r['regrinfo']['agent'], $trans);
                unset($r['regrinfo']['agent']);
                $r['regrinfo']['domain']['sponsor'] = $sponsor;
            }

            $r = format_dates($r, '-mdy');
        } else {
            $r['regrinfo']['registered'] = 'no';
        }

        $r['regyinfo']['referrer'] = 'http://www.domain-registry.nl';
        $r['regyinfo']['registrar'] = 'DNS Belgium';

        return $r;
    }
}

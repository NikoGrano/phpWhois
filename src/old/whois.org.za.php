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

if (!\defined('__ORG_ZA_HANDLER__')) {
    \define('__ORG_ZA_HANDLER__', 1);
}

class org_za_handler
{
    public function parse($data, $query)
    {
        $items = [
            'domain.status'  => 'Status:',
            'domain.nserver' => 'Domain name servers in listed order:',
            'domain.changed' => 'Record last updated on',
            'owner'          => 'rwhois search on',
            'admin'          => 'Administrative Contact:',
            'tech'           => 'Technical Contact:',
            'billing'        => 'Billing Contact:',
            '#'              => 'Search Again',
        ];

        $r = [];
        $r['regrinfo'] = get_blocks($data['rawdata'], $items);

        if (isset($r['regrinfo']['domain']['status'])) {
            $r['regrinfo']['registered'] = 'yes';
            $r['regrinfo']['domain']['handler'] = \strtok(\array_shift($r['regrinfo']['owner']), ' ');
            $r['regrinfo'] = get_contacts($r['regrinfo']);
        } else {
            $r['regrinfo']['registered'] = 'no';
        }

        $r['regyinfo']['referrer'] = 'http://www.org.za';
        $r['regyinfo']['registrar'] = 'The ORG.ZA Domain';

        return $r;
    }
}

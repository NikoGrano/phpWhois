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

if (!\defined('__WS_HANDLER__')) {
    \define('__WS_HANDLER__', 1);
}

use phpWhois\WhoisClient;

require_once 'whois.parser.php';

class ws_handler extends WhoisClient
{
    public function parse($data_str, $query)
    {
        $items = [
            'Domain Name:'                      => 'domain.name',
            'Registrant Name:'                  => 'owner.organization',
            'Registrant Email:'                 => 'owner.email',
            'Domain Created:'                   => 'domain.created',
            'Domain Last Updated:'              => 'domain.changed',
            'Registrar Name:'                   => 'domain.sponsor',
            'Current Nameservers:'              => 'domain.nserver.',
            'Administrative Contact Email:'     => 'admin.email',
            'Administrative Contact Telephone:' => 'admin.phone',
            'Registrar Whois:'                  => 'rwhois',
        ];

        $r = [];
        $r['regrinfo'] = generic_parser_b($data_str['rawdata'], $items, 'ymd');

        $r['regyinfo']['referrer'] = 'http://www.samoanic.ws';
        $r['regyinfo']['registrar'] = 'Samoa Nic';

        if (!empty($r['regrinfo']['domain']['name'])) {
            $r['regrinfo']['registered'] = 'yes';

            if (isset($r['regrinfo']['rwhois'])) {
                if ($this->deepWhois) {
                    $r['regyinfo']['whois'] = $r['regrinfo']['rwhois'];
                    $r = $this->deepWhois($query, $r);
                }

                unset($r['regrinfo']['rwhois']);
            }
        } else {
            $r['regrinfo']['registered'] = 'no';
        }

        return $r;
    }
}

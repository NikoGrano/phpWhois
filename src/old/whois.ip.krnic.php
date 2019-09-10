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

if (!\defined('__KRNIC_HANDLER__')) {
    \define('__KRNIC_HANDLER__', 1);
}

require_once 'whois.parser.php';

class krnic_handler
{
    public function parse($data_str, $query)
    {
        $blocks = [
            'owner1'          => '[ Organization Information ]',
            'tech1'           => '[ Technical Contact Information ]',
            'owner2'          => '[ ISP Organization Information ]',
            'admin2'          => '[ ISP IP Admin Contact Information ]',
            'tech2'           => '[ ISP IP Tech Contact Information ]',
            'admin3'          => '[ ISP IPv4 Admin Contact Information ]',
            'tech3'           => '[ ISP IPv4 Tech Contact Information ]',
            'abuse'           => '[ ISP Network Abuse Contact Information ]',
            'network.inetnum' => 'IPv4 Address       :',
            'network.name'    => 'Network Name       :',
            'network.mnt-by'  => 'Connect ISP Name   :',
            'network.created' => 'Registration Date  :',
        ];

        $items = [
            'Orgnization ID     :' => 'handle',
            'Org Name      :'      => 'organization',
            'Org Name           :' => 'organization',
            'Name          :'      => 'name',
            'Name               :' => 'name',
            'Org Address   :'      => 'address.street',
            'Zip Code      :'      => 'address.pcode',
            'State         :'      => 'address.state',
            'Address            :' => 'address.street',
            'Zip Code           :' => 'address.pcode',
            'Phone         :'      => 'phone',
            'Phone              :' => 'phone',
            'Fax           :'      => 'fax',
            'E-Mail        :'      => 'email',
            'E-Mail             :' => 'email',
        ];

        $b = get_blocks($data_str, $blocks);

        $r = [];
        if (isset($b['network'])) {
            $r['network'] = $b['network'];
        }

        if (isset($b['owner1'])) {
            $r['owner'] = generic_parser_b($b['owner1'], $items, 'Ymd', false);
        } elseif (isset($b['owner2'])) {
            $r['owner'] = generic_parser_b($b['owner2'], $items, 'Ymd', false);
        }

        if (isset($b['admin2'])) {
            $r['admin'] = generic_parser_b($b['admin2'], $items, 'Ymd', false);
        } elseif (isset($b['admin3'])) {
            $r['admin'] = generic_parser_b($b['admin3'], $items, 'Ymd', false);
        }

        if (isset($b['tech1'])) {
            $r['tech'] = generic_parser_b($b['tech1'], $items, 'Ymd', false);
        } elseif (isset($b['tech2'])) {
            $r['tech'] = generic_parser_b($b['tech2'], $items, 'Ymd', false);
        } elseif (isset($b['tech3'])) {
            $r['tech'] = generic_parser_b($b['tech3'], $items, 'Ymd', false);
        }
        if (isset($b['abuse'])) {
            $r['abuse'] = generic_parser_b($b['abuse'], $items, 'Ymd', false);
        }

        $r = format_dates($r, 'Ymd');

        $r = ['regrinfo' => $r];
        $r['regyinfo']['type'] = 'ip';
        $r['regyinfo']['registrar'] = 'Korean Network Information Centre';

        return $r;
    }
}

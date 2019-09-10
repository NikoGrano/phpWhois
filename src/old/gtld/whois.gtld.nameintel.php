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

if (!\defined('__NAMEINTEL_HANDLER__')) {
    \define('__NAMEINTEL_HANDLER__', 1);
}

require_once 'whois.parser.php';

class nameintel_handler
{
    public function parse($data_str, $query)
    {
        $items = [
            'owner'          => 'Registrant Contact:',
            'admin'          => 'Administrative Contact:',
            'tech'           => 'Technical Contact',
            'domain.name'    => 'Domain Name:',
            'domain.status'  => 'Status:',
            'domain.nserver' => 'Name Server:',
            'domain.created' => 'Creation Date:',
            'domain.expires' => 'Expiration Date:',
        ];

        $r = easy_parser($data_str, $items, 'dmy', [], false, true);

        if (isset($r['domain']['sponsor']) && \is_array($r['domain']['sponsor'])) {
            $r['domain']['sponsor'] = $r['domain']['sponsor'][0];
        }

        foreach ($r as $key => $part) {
            if (isset($part['address'])) {
                $r[$key]['organization'] = \array_shift($r[$key]['address']);
                $r[$key]['address']['country'] = \array_pop($r[$key]['address']);
            }
        }

        return $r;
    }
}

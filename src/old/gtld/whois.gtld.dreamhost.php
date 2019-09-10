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

if (!\defined('__DREAMHOST_HANDLER__')) {
    \define('__DREAMHOST_HANDLER__', 1);
}

require_once 'whois.parser.php';

class dreamhost_handler
{
    public function parse($data_str, $query)
    {
        $items = [
            'owner'          => 'Registrant Contact:',
            'admin'          => 'Administrative Contact:',
            'tech'           => 'Technical Contact:',
            'billing'        => 'Billing Contact:',
            'domain.name'    => 'Domain Name:',
            'domain.nserver' => 'Domain servers in listed order:',
            'domain.created' => 'Record created on',
            'domain.expires' => 'Record expires on',
        ];

        $r = easy_parser($data_str, $items, 'dmy', [], false, true);
        if (isset($r['domain']['sponsor']) && \is_array($r['domain']['sponsor'])) {
            $r['domain']['sponsor'] = $r['domain']['sponsor'][0];
        }

        return $r;
    }
}

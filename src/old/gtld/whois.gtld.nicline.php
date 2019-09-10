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

if (!\defined('__NICLINE_HANDLER__')) {
    \define('__NICLINE_HANDLER__', 1);
}

require_once 'whois.parser.php';

class nicline_handler
{
    public function parse($data_str, $query)
    {
        $items = [
            'owner'           => 'Registrant:',
            'admin'           => 'Administrative contact:',
            'tech'            => 'Technical contact:',
            'domain.name'     => 'Domain name:',
            'domain.nserver.' => 'Domain servers in listed order:',
            'domain.created'  => 'Created:',
            'domain.expires'  => 'Expires:',
            'domain.changed'  => 'Last updated:',
        ];

        return easy_parser($data_str, $items, 'dmy');
    }
}

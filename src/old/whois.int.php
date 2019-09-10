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

if (!\defined('__INT_HANDLER__')) {
    \define('__INT_HANDLER__', 1);
}

require_once 'whois.gtld.iana.php';

class int_handler
{
    public function parse($data_str, $query)
    {
        $iana = new iana_handler();
        $r = [];
        $r['regrinfo'] = $iana->parse($data_str['rawdata'], $query);
        $r['regyinfo']['referrer'] = 'http://www.iana.org/int-dom/int.htm';
        $r['regyinfo']['registrar'] = 'Internet Assigned Numbers Authority';

        return $r;
    }
}

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

if (!\defined('__AFRINIC_HANDLER__')) {
    \define('__AFRINIC_HANDLER__', 1);
}

class afrinic_handler
{
    public function parse($data_str, $query)
    {
        $translate = [
            'fax-no'       => 'fax',
            'e-mail'       => 'email',
            'nic-hdl'      => 'handle',
            'person'       => 'name',
            'netname'      => 'name',
            'organisation' => 'handle',
            'org-name'     => 'organization',
            'org-type'     => 'type',
        ];

        $contacts = [
            'admin-c' => 'admin',
            'tech-c'  => 'tech',
            'org'     => 'owner',
        ];

        $r = generic_parser_a($data_str, $translate, $contacts, 'network', 'Ymd');

        if (isset($r['network']['descr'])) {
            $r['owner']['organization'] = $r['network']['descr'];
            unset($r['network']['descr']);
        }

        if (isset($r['owner']['remarks']) && \is_array($r['owner']['remarks'])) {
            while (list($key, $val) = \each($r['owner']['remarks'])) {
                $pos = \mb_strpos($val, 'rwhois://');

                if (false !== $pos) {
                    $r['rwhois'] = \strtok(\mb_substr($val, $pos), ' ');
                }
            }
        }

        $r = ['regrinfo' => $r];
        $r['regyinfo']['type'] = 'ip';
        $r['regyinfo']['registrar'] = 'African Network Information Center';

        return $r;
    }
}

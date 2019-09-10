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

if (!\defined('__RO_HANDLER__')) {
    \define('__RO_HANDLER__', 1);
}

require_once 'whois.parser.php';

/**
 * @TODO BUG
 * - date on ro could be given as "mail date" (ex: updated field)
 * - multiple person for one role, ex: news.ro
 * - seems the only role listed is registrant
 */
class ro_handler
{
    public function parse($data_str, $query)
    {
        $translate = [
            'fax-no'            => 'fax',
            'e-mail'            => 'email',
            'nic-hdl'           => 'handle',
            'person'            => 'name',
            'address'           => 'address.',
            'domain-name'       => '',
            'updated'           => 'changed',
            'registration-date' => 'created',
            'domain-status'     => 'status',
            'nameserver'        => 'nserver',
        ];

        $contacts = [
            'admin-contact'     => 'admin',
            'technical-contact' => 'tech',
            'zone-contact'      => 'zone',
            'billing-contact'   => 'billing',
        ];

        $extra = [
            'postal code:' => 'address.pcode',
        ];

        $reg = generic_parser_a($data_str['rawdata'], $translate, $contacts, 'domain', 'Ymd');

        if (isset($reg['domain']['description'])) {
            $reg['owner'] = get_contact($reg['domain']['description'], $extra);
            unset($reg['domain']['description']);

            foreach ($reg as $key => $item) {
                if (isset($item['address'])) {
                    $data = $item['address'];
                    unset($reg[$key]['address']);
                    $reg[$key] = \array_merge($reg[$key], get_contact($data, $extra));
                }
            }

            $reg['registered'] = 'yes';
        } else {
            $reg['registered'] = 'no';
        }

        $r = [];
        $r['regrinfo'] = $reg;
        $r['regyinfo'] = [
            'referrer'  => 'http://www.nic.ro',
            'registrar' => 'nic.ro',
        ];

        return $r;
    }
}

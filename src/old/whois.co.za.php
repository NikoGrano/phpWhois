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

if (!\defined('__CO_ZA_HANDLER__')) {
    \define('__CO_ZA_HANDLER__', 1);
}

require_once 'whois.parser.php';

class co_Za_handler
{
    public function parse($data_str, $query)
    {
        $items = [
            '0a. lastupdate             :' => 'domain.changed',
            '1a. domain                 :' => 'domain.name',
            '2b. registrantpostaladdress:' => 'owner.address.address.0',
            '2f. billingaccount         :' => 'billing.name',
            '2g. billingemail           :' => 'billing.email',
            '2i. invoiceaddress         :' => 'billing.address',
            '2j. registrantphone        :' => 'owner.phone',
            '2k. registrantfax          :' => 'owner.fax',
            '2l. registrantemail        :' => 'owner.email',
            '4a. admin                  :' => 'admin.name',
            '4c. admincompany           :' => 'admin.organization',
            '4d. adminpostaladdr        :' => 'admin.address',
            '4e. adminphone             :' => 'admin.phone',
            '4f. adminfax               :' => 'admin.fax',
            '4g. adminemail             :' => 'admin.email',
            '5a. tec                    :' => 'tech.name',
            '5c. teccompany             :' => 'tech.organization',
            '5d. tecpostaladdr          :' => 'tech.address',
            '5e. tecphone               :' => 'tech.phone',
            '5f. tecfax                 :' => 'tech.fax',
            '5g. tecemail               :' => 'tech.email',
            '6a. primnsfqdn             :' => 'domain.nserver.0',
            '6e. secns1fqdn             :' => 'domain.nserver.1',
            '6i. secns2fqdn             :' => 'domain.nserver.2',
            '6m. secns3fqdn             :' => 'domain.nserver.3',
            '6q. secns4fqdn             :' => 'domain.nserver.4',
        ];

        $r = [];
        $r['regrinfo'] = generic_parser_b($data_str['rawdata'], $items);

        $r['regyinfo']['referrer'] = 'http://www.co.za';
        $r['regyinfo']['registrar'] = 'UniForum Association';

        return $r;
    }
}

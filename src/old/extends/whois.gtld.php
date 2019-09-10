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

if (!\defined('__GTLD_HANDLER__')) {
    \define('__GTLD_HANDLER__', 1);
}

use phpWhois\WhoisClient;

require_once 'whois.parser.php';

class gtld_handler extends WhoisClient
{
    public $REG_FIELDS = [
        'Domain Name:'     => 'regrinfo.domain.name',
        'Registrar:'       => 'regyinfo.registrar',
        'Whois Server:'    => 'regyinfo.whois',
        'Referral URL:'    => 'regyinfo.referrer',
        'Name Server:'     => 'regrinfo.domain.nserver.', // identical descriptors
        'Updated Date:'    => 'regrinfo.domain.changed',
        'Last Updated On:' => 'regrinfo.domain.changed',
        'EPP Status:'      => 'regrinfo.domain.epp_status.',
        'Status:'          => 'regrinfo.domain.status.',
        'Creation Date:'   => 'regrinfo.domain.created',
        'Created On:'      => 'regrinfo.domain.created',
        'Expiration Date:' => 'regrinfo.domain.expires',
        'Updated Date:'    => 'regrinfo.domain.changed',
        'No match for '    => 'nodomain',
    ];

    public function parse($data, $query)
    {
        $this->query = [];
        $this->result = generic_parser_b($data['rawdata'], $this->REG_FIELDS, 'dmy');

        unset($this->result['registered']);

        if (isset($this->result['nodomain'])) {
            unset($this->result['nodomain']);
            $this->result['regrinfo']['registered'] = 'no';

            return $this->result;
        }

        if ($this->deepWhois) {
            $this->result = $this->deepWhois($query, $this->result);
        }

        // Next server could fail to return data
        if (empty($this->result['rawdata']) || \count($this->result['rawdata']) < 3) {
            $this->result['rawdata'] = $data['rawdata'];
        }

        // Domain is registered no matter what next server says
        $this->result['regrinfo']['registered'] = 'yes';

        return $this->result;
    }
}

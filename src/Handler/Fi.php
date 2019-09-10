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

namespace phpWhois\Handler;

class Fi extends HandlerBase
{
    protected $dateFormat = ['d.m.Y H:i:s', 'd.m.Y'];

    protected $server = 'whois.ficora.fi';

    protected $patternUpdated = ['/modified/i'];

    protected function mutateKeyValues(array $keyValues): array
    {
        $return = [];
        foreach ($keyValues as $key => $value) {
            $return[\str_replace('.', '', $key)] = $value;
            unset($keyValues[$key]);
        }
        $return['updated'] = \str_replace(' <<<', '', $return['>>> Last update of WHOIS database']);
        unset($return['>>> Last update of WHOIS database']);

        return $return;
    }
}

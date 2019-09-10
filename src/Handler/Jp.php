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

use phpWhois\Query;

/**
 * TODO: strip brackets [] around keys.
 */
class Jp extends HandlerBase
{
    // TODO: No single idea why trailing parentheses is missing but it works
    protected $dateFormat = ['Y/m/d', 'Y/m/d H:i:s (T'];

    protected $server = 'whois.jprs.jp';

    protected $patternRowSeparator = [
        '/(\])/i',
    ];

    public function __construct(Query $query)
    {
        parent::__construct($query);

        // Request response in English
        $this->getQuery()->addParam('/e');
    }
}

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

class Kz extends HandlerBase
{
    // TODO: quite dirty hack for parsing 2012-11-28 03:16:59 (GMT+0:00)
    protected $dateFormat = ['Y-m-d H:i:s (T'];
}

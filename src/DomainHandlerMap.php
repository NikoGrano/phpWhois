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

namespace phpWhois;

class DomainHandlerMap
{
    /**
     * @var array Mappings from domain name to handler class
     */
    private $map = [
        '/\.bg$/i'               => Handler\Bg::class,
        '/\.fr$/i'               => Handler\Registrar\Frnic::class,
        '/\.hm$/i'               => Handler\Hm::class,
        '/\.hu$/i'               => Handler\Hu::class,
        '/\.im$/i'               => Handler\Im::class,
        '/\.jp$/i'               => Handler\Jp::class,
        '/\.kr$/i'               => Handler\Kr::class,
        '/\.(kz|xn--80ao21a)$/i' => Handler\Kz::class, //.қаз
        '/\.pf$/i'               => Handler\Pf::class,
        '/\.pl$/i'               => Handler\Pl::class,
        '/\.pm$/i'               => Handler\Registrar\Frnic::class,
        '/\.pt$/i'               => Handler\Pt::class,
        '/\.re$/i'               => Handler\Registrar\Frnic::class,
        '/\.(ru|su|xn--p1ai)$/i' => Handler\Ru::class, //.рф
        '/\.sk$/i'               => Handler\Sk::class,
        '/\.sm$/i'               => Handler\Sm::class,
        '/\.tf$/i'               => Handler\Registrar\Frnic::class,
        '/\.uy$/i'               => Handler\Uy::class,
        '/\.wf$/i'               => Handler\Registrar\Frnic::class,
        '/\.yt$/i'               => Handler\Registrar\Frnic::class,
//        '/^(?:[a-z0-9\-]+?\.){1,2}ru$/i' => Handler\Registrar\NicRu::class,
//        '/^(?:[a-z0-9\-]+?\.){1,2}su$/i' => Handler\Registrar\NicRu::class,
//        '/^(?:[a-z0-9\-]+?\.){1}ru\.net$/i' => Handler\Registrar\NicRu::class,
//        '/^(?:[a-z0-9\-]+?\.){1}\.moscow$/i' => Handler\Registrar\NicRu::class,
    ];

    /**
     * Return map pattern => handler.
     *
     * @return array
     */
    protected function getMap()
    {
        return $this->map;
    }

    /**
     * Look for domain handler in the predefined map.
     *
     * @param string $address
     *
     * @return string|null
     */
    public function findHandler($address)
    {
        foreach ($this->getMap() as $pattern => $class) {
            if (\preg_match($pattern, $address)) {
                return $class;
            }
        }

        return null;
    }
}

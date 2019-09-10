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

namespace phpWhois;

use TrueBV\Punycode;

/**
 * Utilities for parsing and validating queries.
 */
class QueryUtils
{
    /**
     * Check if ip address is valid.
     *
     * @param string $ip     IP address for validation
     * @param string $type   Type of ip address. Possible value are: any, ipv4, ipv6
     * @param bool   $strict If true - fail validation on reserved and private ip ranges
     *
     * @return bool True if ip is valid. False otherwise
     */
    public function validIp($ip, $type = 'any', $strict = true)
    {
        switch ($type) {
            case 'any':
                return $this->validIpv4($ip, $strict) || $this->validIpv6($ip, $strict);
            case 'ipv4':
                return $this->validIpv4($ip, $strict);
            case 'ipv6':
                return $this->validIpv6($ip, $strict);
        }

        return false;
    }

    /**
     * Check if given IP is a valid ipv4 address and doesn't belong to private and
     * reserved ranges.
     *
     * @param string $ip     Ip address
     * @param bool   $strict If true - fail validation on reserved and private ip ranges
     *
     * @return bool
     */
    public function validIpv4($ip, $strict = true)
    {
        $flags = FILTER_FLAG_IPV4;
        if ($strict) {
            $flags = FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE;
        }

        if (false !== \filter_var($ip, FILTER_VALIDATE_IP, ['flags' => $flags])) {
            return true;
        }

        return false;
    }

    /**
     * Check if given IP is a valid ipv6 address and doesn't belong to private ranges.
     *
     * @param string $ip     Ip address
     * @param bool   $strict If true - fail validation on reserved and private ip ranges
     *
     * @return bool
     */
    public function validIpv6($ip, $strict = true)
    {
        $flags = FILTER_FLAG_IPV6;
        if ($strict) {
            $flags = FILTER_FLAG_IPV6 | FILTER_FLAG_NO_PRIV_RANGE;
        }

        if (false !== \filter_var($ip, FILTER_VALIDATE_IP, ['flags' => $flags])) {
            return true;
        }

        return false;
    }

    /**
     * Check if given domain name is valid.
     *
     * @param string $domain Domain name to check
     *
     * @return bool
     */
    public function validDomain($domain)
    {
        $domain = (new Punycode())->encode($domain);

        $patterns = [
            '/^[a-z\d\.\-]*\.[a-z]{2,63}$/i',
            '/^[a-z\d\.\-]*\.xn--[a-z\d]{4,59}$/i',
        ];
        foreach ($patterns as $pattern) {
            if (\preg_match($pattern, $domain)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if given AS is valid.
     *
     * @param string $as AS number
     *
     * @return bool
     */
    public function validAS($as)
    {
        $pattern = '/^[a-z\d\-]*$/i';
        if (\preg_match($pattern, $as)) {
            return true;
        }

        return false;
    }
}

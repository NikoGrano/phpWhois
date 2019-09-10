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

use TrueBV\Punycode;

final class Query
{
    public const QTYPE_UNKNOWN = -1;
    public const QTYPE_DOMAIN = 1;
    public const QTYPE_IPV4 = 2;
    public const QTYPE_IPV6 = 3;
    public const QTYPE_AS = 4;

    /**
     * @var int Query type (see constants)
     */
    private $type = self::QTYPE_UNKNOWN;

    /**
     * @var string Original address received
     */
    private $addressOrig;

    /**
     * @var string Address optimized for querying the whois server
     */
    private $address;

    /**
     * @var string[] Additional params to apply to address when querying whois server
     */
    private $params = [];

    /**
     * Query constructor.
     *
     * @param string|null $address
     * @param   string[]    Array of params for whois server
     */
    public function __construct($address = null, array $params = [])
    {
        if (null !== $address) {
            $this->setAddress($address);
        }

        foreach ($params as $param) {
            $this->addParam($param);
        }
    }

    /**
     * Set address, make necessary checks and transformations.
     *
     * @api
     *
     * @param string $address
     *
     * @return $this
     *
     * @throws \InvalidArgumentException if address is not recognized
     */
    public function setAddress($address): self
    {
        $type = $this->guessType($address);

        if (self::QTYPE_UNKNOWN === $type) {
            throw new \InvalidArgumentException('Address is not recognized, can\'t find whois server');
        }
        $this->setType($type);

        $this->setAddressOrig($address);

        $this->address = $this->optimizeAddress($address);

        return $this;
    }

    /**
     * @return string|null Address, optimized for querying whois server
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * Set original unoptimized address.
     *
     * @param string $address
     *
     * @return $this
     */
    private function setAddressOrig($address): self
    {
        $this->addressOrig = $address;

        return $this;
    }

    /**
     * Get original unoptimized address.
     *
     * @return string Original unoptimized address
     */
    public function getAddressOrig(): string
    {
        return $this->addressOrig;
    }

    /**
     * Check if class instance has valid address set.
     *
     * @return bool
     */
    public function hasData(): bool
    {
        return null !== $this->getAddress();
    }

    /**
     * Set query type (See constants).
     *
     * @param int $type
     *
     * @return $this
     */
    private function setType($type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get params array.
     *
     * @return string[]
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * Add param to query.
     *
     * @param string $param
     *
     * @return $this
     */
    public function addParam($param): self
    {
        $this->params[] = (string) $param;

        return $this;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type ?: self::QTYPE_UNKNOWN;
    }

    /**
     * Find the type of a given address and make some optimizations like removing www.
     *
     * @api
     *
     * @param string $address
     *
     * @return string optimized address
     */
    public function optimizeAddress($address): string
    {
        $type = $this->guessType($address);
        if (self::QTYPE_DOMAIN === $type) {
            $address = (new Punycode())->encode($address);

            $address_nowww = \preg_replace('/^www./i', '', $address);
            if ((new QueryUtils())->validDomain($address_nowww)) {
                $address = $address_nowww;
            }
        }

        return $address;
    }

    /**
     * Guess address type.
     *
     * @param string $address
     *
     * @return int Query type
     */
    public function guessType($address): int
    {
        $q = new QueryUtils();

        if ($q->validIp($address, 'ipv4', false)) {
            return $q->validIp($address, 'ipv4') ? self::QTYPE_IPV4 : self::QTYPE_UNKNOWN;
        }
        if ($q->validIp($address, 'ipv6', false)) {
            return $q->validIp($address, 'ipv6') ? self::QTYPE_IPV6 : self::QTYPE_UNKNOWN;
        }
        if ($q->validDomain($address)) {
            return self::QTYPE_DOMAIN;
            // TODO: replace with AS validator
        }
        if ($address && \is_string($address) && false === \mb_strpos($address, '.')) {
            return self::QTYPE_AS;
        }

        return self::QTYPE_UNKNOWN;
    }
}

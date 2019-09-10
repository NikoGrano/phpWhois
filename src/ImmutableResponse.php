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

/**
 * Response from WhoisServer.
 */
final class ImmutableResponse
{
    /**
     * @var Query
     */
    private $query;
    /**
     * @var string
     */
    private $rawResponse;
    /**
     * @var \DateTimeImmutable|null
     */
    private $expires;
    /**
     * @var \DateTimeImmutable|null
     */
    private $registered;
    /**
     * @var \DateTimeImmutable|null
     */
    private $updated;
    /**
     * @var array
     */
    private $keyValues;

    public function __construct(
        Query $query,
        string $rawResponse,
        ?\DateTimeImmutable $expires,
        ?\DateTimeImmutable $registered,
        ?\DateTimeImmutable $updated,
        array $keyValues
    ) {
        $this->query = $query;
        $this->rawResponse = $rawResponse;
        $this->expires = $expires;
        $this->registered = $registered;
        $this->updated = $updated;
        $this->keyValues = $keyValues;
    }

    /**
     * @return Query
     */
    public function getQuery(): Query
    {
        return $this->query;
    }

    /**
     * @return string
     */
    public function getRawResponse(): string
    {
        return $this->rawResponse;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getExpires(): ?\DateTimeImmutable
    {
        return $this->expires;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getRegistered(): ?\DateTimeImmutable
    {
        return $this->registered;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getUpdated(): ?\DateTimeImmutable
    {
        return $this->updated;
    }

    /**
     * @return array
     */
    public function getKeyValues(): array
    {
        return $this->keyValues;
    }

    /**
     * Try find value, otherwise default to null or specified value.
     *
     * @param string $key
     * @param null   $default
     *
     * @return mixed
     */
    public function getValue(string $key, $default = null)
    {
        return isset($this->keyValues[$key]) ? $key : $default;
    }
}

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
class Response
{
    /**
     * @var Query Query object
     */
    private $query;

    /**
     * @var string Raw data received from the whois server
     */
    private $raw;

    /**
     * @var array Parsed data
     */
    private $parsed;

    /**
     * Response constructor.
     *
     * @param Query|null $query
     */
    public function __construct(Query $query = null)
    {
        $this->setQuery($query);
    }

    /**
     * Set not parsed raw response from the whois server.
     *
     * @param string|null $raw
     *
     * @return $this
     */
    public function setRaw($raw = null)
    {
        $this->raw = $raw;

        return $this;
    }

    /**
     * Get not parsed raw response from whois server.
     *
     * @return string|null
     */
    public function getRaw()
    {
        return $this->raw;
    }

    /**
     * Set query.
     *
     * @param Query|null $query
     *
     * @return $this
     */
    public function setQuery(Query $query = null)
    {
        $this->query = $query;

        return $this;
    }

    /**
     * Get current query object.
     *
     * @return Query|null
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * Set parsed.
     *
     * @param array $parsed
     *
     * @return $this
     */
    public function setParsed(array $parsed = [])
    {
        $this->parsed = $parsed;

        return $this;
    }

    /**
     * Get parsed array.
     *
     * @return array
     */
    public function getParsed()
    {
        return $this->parsed;
    }

    /**
     * Look for the key in the rows array
     * TODO: Search across the blocks.
     *
     * @param string $key
     *
     * @return string|null
     */
    public function getByKey($key)
    {
        $parsed = $this->getParsed();

        if (\array_key_exists('keyValue', $parsed) && \array_key_exists($key, $parsed['keyValue'])) {
            return $parsed['keyValue'][$key];
        }

        return null;
    }

    public function getData()
    {
        $result = [
            'query' => [
                'address'     => $this->query->getAddress(),
                'addressOrig' => $this->query->getAddressOrig(),
            ],
            // TODO: Add provider object to the response
            /*'server' => [
                'name' => $this->provider->getServer(),
                'port' => $this->provider->getPort(),
                'errno' => $this->provider->getConnectionErrNo(),
                'errstr' => $this->provider->getConnectionErrStr(),
            ],*/
            'responseRaw' => $this->getRaw(),
        ];

        return $result;
    }

    public function getJson()
    {
        return \json_encode($this->getData());
    }
}

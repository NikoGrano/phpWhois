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

namespace phpWhois\Provider;

use phpWhois\Query;
use phpWhois\Response;

/**
 * Abstract class Provider - defines the algorithms for communicating with various whois servers.
 */
abstract class ProviderAbstract
{
    /**
     * @var string Whois server to query
     */
    protected $server;

    /**
     * @var int Whois server port
     */
    protected $port;

    /**
     * @var int Timeout for connecting to the server
     */
    protected $timeout = 10;

    /**
     * @var int Number of retries to connect to the server. 0 - connect once (no retries)
     */
    protected $retry = 0;

    /**
     * @var int Number of seconds to sleep before retry
     */
    protected $sleep = 1;

    /**
     * @var int Connection error number
     */
    protected $connectionErrNo;

    /**
     * @var string Connection error string
     */
    protected $connectionErrStr;

    /**
     * @var resource Connection pointer
     */
    protected $connectionPointer;

    /**
     * @var Query
     */
    protected $query;

    /**
     * @var string This is a raw query which will be sent to the Whois server (e.g. add "\r\n" to domain)
     */
    protected $rawQuery;

    /**
     * Connect to the defined server.
     *
     * @return $this
     *
     * @throws \InvalidArgumentException if server is not specified
     */
    abstract protected function connect();

    /**
     * Perform a request.
     *
     * Perform a request to the defined whois server through the established connection
     * and return raw response
     *
     * @return string
     */
    abstract protected function performRequest(): string;

    /**
     * @param Query  $query
     * @param string $server
     */
    public function __construct(Query $query, string $server)
    {
        $this->setQuery($query);
        $this->setServer($server);
    }

    /**
     * Set server and parse it if it is in a host:port format.
     *
     * @param string $server
     *
     * @return $this
     */
    public function setServer(string $server): self
    {
        $parts = \explode(':', $server);
        $this->server = $parts[0];

        if (2 === \count($parts)) {
            $this->setPort((int)$parts[1]);
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getServer(): string
    {
        return $this->server;
    }

    /**
     * Set whois server port number.
     *
     * @param int $port
     *
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function setPort($port): self
    {
        if (!\is_int($port)) {
            throw new \InvalidArgumentException('Port number must be an integer');
        }
        $this->port = $port;

        return $this;
    }

    /**
     * Get whois server port number.
     *
     * @return int|null
     */
    public function getPort(): ?int
    {
        return $this->port;
    }

    /**
     * Set connection timeout.
     *
     * @param int $timeout
     *
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function setTimeout($timeout = 10): self
    {
        if (!\is_int($timeout)) {
            throw new \InvalidArgumentException('Timeout must be integer number of seconds');
        }
        $this->timeout = $timeout;

        return $this;
    }

    /**
     * Get connection timeout.
     *
     * @return int
     */
    public function getTimeout(): int
    {
        return $this->timeout;
    }

    /**
     * Set number of connection retries.
     *
     * @param int $retry Number of retries. 0 - connect once (no retries)
     *
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function setRetry($retry = 0): self
    {
        if (!\is_int($retry)) {
            throw new \InvalidArgumentException('Number of retries must be integer value');
        }
        $this->retry = $retry;

        return $this;
    }

    /**
     * Get number of retries.
     *
     * @return int Number of retries. 0 - connect once (no retries)
     */
    public function getRetry(): int
    {
        return $this->retry;
    }

    /**
     * Set number of seconds to sleep before next retry.
     *
     * @param int $sleep
     *
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function setSleep($sleep = 0): self
    {
        if (!\is_int($sleep)) {
            throw new \InvalidArgumentException('Number of seconds to sleep must be integer');
        }

        $this->sleep = $sleep;

        return $this;
    }

    /**
     * Get number of seconds to sleep before next retry.
     *
     * @return int
     */
    public function getSleep(): int
    {
        return $this->sleep;
    }

    /**
     * Set connection error number.
     *
     * @param int $errno
     *
     * @return $this
     */
    protected function setConnectionErrNo($errno): self
    {
        $this->connectionErrNo = $errno;

        return $this;
    }

    /**
     * Get connection error number.
     *
     * @return int|null
     */
    public function getConnectionErrNo(): ?int
    {
        return $this->connectionErrNo;
    }

    /**
     * Set connection error message as a string.
     *
     * @param string $errstr
     *
     * @return $this
     */
    protected function setConnectionErrStr($errstr): self
    {
        $this->connectionErrStr = $errstr;

        return $this;
    }

    /**
     * Get connection error message as a string.
     *
     * @return string|null
     */
    public function getConnectionErrStr(): ?string
    {
        return $this->connectionErrStr;
    }

    /**
     * Set connection pointer.
     *
     * @param resource $pointer
     *
     * @return $this
     *
     * @throws \InvalidArgumentException if pointer is not valid
     */
    protected function setConnectionPointer($pointer)
    {
        if ($pointer) {
            $this->connectionPointer = $pointer;
        } else {
            throw new \InvalidArgumentException('Valid connection pointer (resource) must be provided');
        }

        return $this;
    }

    /**
     * Get connection pointer.
     *
     * @return resource|null
     */
    protected function getConnectionPointer()
    {
        return $this->connectionPointer;
    }

    /**
     * Check if connection is established with the whois server.
     *
     * @return bool
     */
    protected function isConnected()
    {
        if ($this->getConnectionPointer()) {
            return true;
        }

        return false;
    }

    /**
     * Set query.
     *
     * @param Query $query
     *
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function setQuery(Query $query): self
    {
        if ($query->hasData()) {
            $this->query = $query;
        } else {
            throw new \InvalidArgumentException('Cannot assign an empty query');
        }

        return $this;
    }

    public function getQuery(): Query
    {
        return $this->query;
    }

    /**
     * Set raw query for querying whois server.
     *
     * @param string $rawQuery
     *
     * @return $this
     */
    public function setRawQuery($rawQuery): self
    {
        $this->rawQuery = $rawQuery;

        return $this;
    }

    /**
     * @return string
     */
    public function getRawQuery(): string
    {
        return $this->rawQuery;
    }

    /**
     * Check if instance has set query, server and server port.
     *
     * @return bool
     */
    public function hasData(): bool
    {
        return $this->getQuery()->hasData()
            && !empty($this->getServer())
            && !empty($this->getPort());
    }

    /**
     * Perform a lookup and return Response object.
     *
     * @return string
     */
    public function lookup(): string
    {
        return $this
                ->connect()
                ->performRequest();
    }
}

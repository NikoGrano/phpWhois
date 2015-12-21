<?php
/**
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2
 * @license
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 *
 * @link http://phpwhois.pw
 * @copyright Copyright (c) 2015 Dmitry Lukashin
 */

namespace phpWhois\Provider;

use phpWhois\Query;
use phpWhois\Response;

/**
 * Abstract class Provider - defines the algorithms for communicating with various whois servers
 */
abstract class ProviderAbstract {

    /**
     * @var string  Whois server to query
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
     * @var resource    Connection pointer
     */
    protected $connectionPointer;

    /**
     * @var Query
     */
    protected $query;

    /**
     * @var Response
     */
    protected $response;

    /**
     * Connect to the defined server
     *
     * @return ProviderAbstract
     *
     * @throws \InvalidArgumentException    if server is not specified
     */
    abstract protected function connect();

    /**
     * Perform a request to the defined whois server through the established connection
     *
     * @return mixed
     */
    abstract protected function performRequest();

    /**
     * @param Query     $query
     * @param string    $server
     */
    public function __construct(Query $query, $server)
    {
        $this->setQuery($query);
        $this->setServer($server);
        $this->setResponse(new Response());
    }

    /**
     * Set server and parse it if it is in a host:port format
     *
     * @param string    $server
     *
     * @return ProviderAbstract
     */
    public function setServer($server)
    {
        /**
         * TODO: Handle ipv6 servers here as well
         */
        $parts = explode(':', $server);
        $this->server = $parts[0];
        if (count($parts) == 2) {
            $this->setPort(intval($parts[1]));
        }

        return $this;
    }

    /**
     * @return null|string
     */
    public function getServer()
    {
        return $this->server;
    }

    /**
     * Set whois server port number
     *
     * @param int $port
     * @return ProviderAbstract
     *
     * @throws \InvalidArgumentException
     */
    public function setPort($port)
    {
        if (!is_int($port)) {
            throw new \InvalidArgumentException('Port number must be an integer');
        }
        $this->port = $port;

        return $this;
    }

    /**
     * @return null|int
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * Set connection timeout
     *
     * @param int $timeout
     * @return ProviderAbstract
     *
     * @throws \InvalidArgumentException
     */
    public function setTimeout($timeout = 10)
    {
        if (!is_int($timeout)) {
            throw new \InvalidArgumentException("Timeout must be integer number of seconds");
        }
        $this->timeout = $timeout;

        return $this;
    }

    /**
     * Get connection timeout
     *
     * @return int
     */
    public function getTimeout()
    {
        return $this->timeout;
    }

    /**
     * Set number of connection retries.
     *
     * @param int $retry    Number of retries. 0 - connect once (no retries)
     * @return ProviderAbstract
     *
     * @throws \InvalidArgumentException
     */
    public function setRetry($retry = 0)
    {
        if (!is_int($retry)) {
            throw new \InvalidArgumentException("Number of retries must be integer value");
        }
        $this->retry = 0;

        return $this;
    }

    /**
     * Get number of retries
     *
     * @return int  Number of retries. 0 - connect once (no retries)
     */
    public function getRetry()
    {
        return $this->retry;
    }

    /**
     * Set number of seconds to sleep before next retry
     *
     * @param int $sleep
     * @return ProviderAbstract
     *
     * @throws \InvalidArgumentException
     */
    public function setSleep($sleep = 1)
    {
        if (!is_int($sleep)) {
            throw new \InvalidArgumentException("Number of seconds to sleep must be integer");
        }
        $this->sleep = 0;

        return $this;
    }

    /**
     * Get number of seconds to sleep before next retry
     *
     * @return int
     */
    public function getSleep()
    {
        return $this->sleep;
    }

    /**
     * Set connection pointer
     *
     * @param $pointer
     * @return $this
     *
     * @throws \InvalidArgumentException    if pointer is not valid
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
     * Get connection pointer
     *
     * @return null|resource
     */
    protected function getConnectionPointer()
    {
        return $this->connectionPointer;
    }

    /**
     * Check if connection is established with the whois server
     *
     * @return bool
     */
    protected function isConnected()
    {
        if ($this->getConnectionPointer()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param Query $query
     *
     * @return ProviderAbstract
     *
     * @throws \InvalidArgumentException
     */
    protected function setQuery(Query $query)
    {
        if ($query->hasData()) {
            $this->query = $query;
        } else {
            throw new \InvalidArgumentException('Cannot assign empty query');
        }


        return $this;
    }

    /**
     * Attach response object
     *
     * @param Response $response
     */
    protected function setResponse(Response $response)
    {
        $this->response = $response;
    }

    /**
     * Get response object
     *
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Perform a lookup and return Response object
     *
     * @return Response
     */
    public function lookup()
    {
        $this->connect();
        $this->performRequest();

        return $this->getResponse();
    }
}
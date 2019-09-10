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

namespace phpWhois\Provider;

class WhoisServer extends ProviderAbstract
{
    /**
     * @var int Stream reading timeout
     */
    private $streamTimeout = 7;

    /**
     * @var int Whois server port
     */
    protected $port = 43;

    /**
     * Set stream timeout.
     *
     * @param int $streamTimeout
     *
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    private function setStreamTimeout($streamTimeout = 7)
    {
        if (!\is_int($streamTimeout)) {
            throw new \InvalidArgumentException('Stream timeout must be integer number of seconds');
        }
        $this->streamTimeout = $streamTimeout;

        return $this;
    }

    /**
     * Get stream timeout.
     *
     * @return int
     */
    private function getStreamTimeout()
    {
        return $this->streamTimeout;
    }

    /**
     * {@inheritdoc}
     */
    protected function connect()
    {
        $server = $this->getServer();
        $port = $this->getPort();

        if (!$server || !$port) {
            throw new \InvalidArgumentException('Whois server is not defined. Cannot connect');
        }

        $attempt = 0;
        while ($attempt <= $this->getRetry()) {
            // Sleep before retrying next attempt
            if ($attempt > 0) {
                \sleep($this->getSleep());
            }

            $fp = @\fsockopen('tcp://'.$server, $port, $errno, $errstr, $this->getTimeout());

            $this
                ->setConnectionErrNo($errno)
                ->setConnectionErrStr($errstr);

            if (!$fp) {
                ++$attempt;
            } else {
                \stream_set_timeout($fp, $this->getStreamTimeout());
                \stream_set_blocking($fp, true);
                $this->setConnectionPointer($fp);
                break; // Connection established, exit loop
            }
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function performRequest()
    {
        $raw = null;
        if ($this->isConnected()) {
            $fp = $this->getConnectionPointer();

            $query = $this->getQuery();

            $request = $query->getAddress();
            $request .= \implode('', $query->getParams());
            $request .= "\r\n";

            \fwrite($fp, $request);

            $r = [$fp];
            $w = null;
            $e = null;
            \stream_select($r, $w, $e, $this->getStreamTimeout());

            $raw = \stream_get_contents($fp);

            \fclose($fp);
        }

        return $raw;
    }
}

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

use phpWhois\Handler\HandlerBase;

/**
 * phpWhois main class.
 *
 * This class supposed to be instantiated for using the phpWhois library
 */
class Whois
{
    /**
     * @var HandlerBase Handler for obtaining address whois information
     */
    protected $handler;

    /**
     * @var Query Query object created from the given domain name
     */
    protected $query;

    /**
     * Whois constructor.
     *
     * @param string|null $address Address to query
     */
    public function __construct($address = null)
    {
        // Omitting $this->setAddress()
        $this->setQuery(new Query($address));
    }

    /**
     * Set query instance.
     *
     * @param Query $query Set query instance
     *
     * @return $this
     */
    protected function setQuery(Query $query): self
    {
        $this->query = $query;

        return $this;
    }

    /**
     * Get instance.
     *
     * @return Query
     */
    public function getQuery(): Query
    {
        return $this->query;
    }

    /**
     * Set address.
     *
     * @param string $address Address
     *
     * @return $this
     *
     * @throws \InvalidArgumentException When address is not recognized
     */
    public function setAddress($address): self
    {
        $this->getQuery()->setAddress($address);

        return $this;
    }

    /**
     * Instantiate handler by the given class name.
     *
     * @param string|null $handler Name of handler class. Must inherit HandlerBase
     *
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function setHandler($handler): self
    {
        if (null === $handler) {
            return $this;
        }

        if (!\class_exists($handler)) {
            throw new \InvalidArgumentException('Specified handler class wasn\'t found');
        }

        $handler = new $handler($this->getQuery(), null);

        if (!($handler instanceof HandlerBase)) {
            throw new \InvalidArgumentException('Handler must be an instance of phpWhois\Handler\HandlerBase');
        }

        $this->handler = $handler;

        return $this;
    }

    /**
     * Get handler instance.
     *
     * @return HandlerBase|null
     */
    protected function getHandler(): ?HandlerBase
    {
        return $this->handler;
    }

    /**
     * Perform a lookup of address.
     *
     * 1. Query IANA whois server first to obtain the default whois server.
     * 2. If IANA returned the default server address - query it
     * 3. If special handler is set - try to query it as well
     *
     * @param string|null $address
     *
     * @return ImmutableResponse Response from the whois server
     *
     * @throws \InvalidArgumentException if address is empty
     * @throws \Exception
     */
    public function lookup(?string $address = null): ImmutableResponse
    {
        if (null !== $address) {
            $this->setAddress($address);
        }

        if (!$this->getQuery()->hasData()) {
            throw new \InvalidArgumentException('Address wasn\'t set, can\'t perform a query');
        }

        // If handler is not set yet, try to find a custom handler
        if (!($this->getHandler() instanceof HandlerBase)) {
            $handlerClass = (new DomainHandlerMap())->findHandler($this->getQuery()->getAddress());
            $this->setHandler($handlerClass);
        }

        // If handler isn't set or custom handler doesn't have server address defined - obtain server address from IANA
        if (!($this->getHandler() instanceof HandlerBase) || empty($this->getHandler()->getServer())) {
            $serverAddress = (new HandlerBase($this->getQuery(), 'whois.iana.org'))->lookup()->getByKey('whois');

            if (empty($serverAddress)) {
                throw new \InvalidArgumentException('Cannot find whois server. Consider creating custom handler with predefined server address');
            }

            if (!($this->getHandler() instanceof HandlerBase)) {
                $this->setHandler(HandlerBase::class);
            }

            $this->getHandler()->setServer($serverAddress);
        }

        $lookup = $this->getHandler()->lookup();

        $parsed = $lookup->getParsed();
        $expires = $parsed['dates']['expires'];
        $registered = $parsed['dates']['registered'];
        $updated = $parsed['dates']['updated'];

        return new ImmutableResponse(
            $lookup->getQuery(),
            $lookup->getRaw(),
            false !== $expires ? new \DateTimeImmutable(\date(DATE_ATOM, (int)$expires)) : null,
            false !== $registered ? new \DateTimeImmutable(\date(DATE_ATOM, (int)$registered)) : null,
            false !== $updated ? new \DateTimeImmutable(\date(DATE_ATOM, (int)$updated)) : null,
            $lookup->getParsed()['keyValue']
        );
    }
}

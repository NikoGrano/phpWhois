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

use phpWhois\Provider\ProviderAbstract;
use phpWhois\Provider\WhoisServer;
use phpWhois\Query;
use phpWhois\Response;

class HandlerBase
{
    /**
     * @var string[]|null Date format in php date() format
     */
    protected $dateFormat = null;

    /**
     * @var string[] Patterns for finding Expires field in response
     */
    protected $patternExpires = [
        '/expir(e|y|es|ation)/i',
        '/renew(al)?/i',
        '/paid\-till/i',
        '/validity/i',
        '/billeduntil/i',
    ];

    /**
     * @var string[] Patterns for finding Registered field in response
     */
    protected $patternRegistered = [
        '/creat(ed|ion)/i',
        '/regist(ered|ration)/i',
        '/commencement/i',
    ];

    /**
     * @var string[] Patterns for finding Updated field in response
     */
    protected $patternUpdated = [
        '/update(d)?/i',
        '/modif(y|ied|ication)/i',
        '/changed/i',
    ];

    /**
     * @var array Patterns for realizing that domain is registered
     */
    protected $patternStatusRegistered = [
        '/status$/i' => '/^ok/i',
    ];

    /**
     * @var string[] Patterns for finding name servers
     */
    protected $patternNServer = [
        '/nserver/i',
        '/name server/i',
    ];

    /**
     * @var string[] Indicates that line is a comment
     */
    protected $patternComment = [
        '/^%/i',
    ];

    /**
     * @var string[] Row separators arranged descending by priority
     */
    protected $patternRowSeparator = [
        '/(:)/i',
    ];

    /**
     * @var string Name of whois information class provider. Class must extend ProviderAbstract
     */
    protected $providerClass = WhoisServer::class;

    /**
     * @var string Server address for the provider
     */
    protected $server;

    /**
     * ***************************************************************
     * WARNING: Variables below are not supposed to be overwritten in
     * inherited classes
     * ***************************************************************.
     */

    /**
     * @var array Parsed data
     */
    private $parsed;

    /**
     * @var ProviderAbstract Provider instance
     */
    private $provider;

    /**
     * @var Query
     */
    private $query;

    /**
     * @var string Raw response from whois server
     */
    private $raw;

    /**
     * @var string[] Response rows split by newline
     */
    private $rows;

    /**
     * Handler constructor.
     *
     * @param Query       $query  Query for whois server
     * @param string|null $server Whois server address
     */
    public function __construct(Query $query, $server = null)
    {
        $this->setQuery($query);

        // Default provider is WhoisServer
        $provider = new $this->providerClass($query);
        if (!($provider instanceof ProviderAbstract)) {
            throw new \InvalidArgumentException('Provider class must extend phpWhois\Provider\ProviderAbstract');
        }
        $this->setProvider($provider);

        if (null === $server) {
            $server = $this->getServer();
        }
        $this->setServer($server);
    }

    /**
     * Set query.
     *
     * @param Query $query
     *
     * @return $this
     */
    protected function setQuery(Query $query): self
    {
        $this->query = $query;

        return $this;
    }

    /**
     * Get query.
     *
     * @return Query
     */
    public function getQuery(): Query
    {
        return $this->query;
    }

    /**
     * Set provider server address.
     *
     * @param string $server
     *
     * @return $this
     */
    public function setServer($server): self
    {
        $this->server = $server;
        $this->getProvider()->setServer($server);

        return $this;
    }

    /**
     * Get provider server address.
     *
     * @return string|null
     */
    public function getServer(): ?string
    {
        return $this->server;
    }

    /**
     * Set provider.
     *
     * @param ProviderAbstract $provider
     *
     * @return $this
     */
    protected function setProvider(ProviderAbstract $provider): self
    {
        $this->provider = $provider;

        return $this;
    }

    /**
     * Get provider.
     *
     * @return ProviderAbstract
     */
    public function getProvider(): ProviderAbstract
    {
        return $this->provider;
    }

    /**
     * Set raw response from the whois server.
     *
     * @param string|null $raw
     *
     * @return $this
     */
    public function setRaw($raw): self
    {
        $this->raw = $raw;

        return $this;
    }

    /**
     * Get raw response from the whois server.
     *
     * @return string
     */
    public function getRaw(): string
    {
        return $this->raw;
    }

    /**
     * Set response split into rows by newline.
     *
     * @param string[] $rows
     *
     * @return $this
     */
    protected function setRows(array $rows): self
    {
        $this->rows = $rows;

        return $this;
    }

    /**
     * Get response split by newline.
     *
     * @return array
     */
    public function getRows(): array
    {
        return $this->rows;
    }

    /**
     * Set array with parsed data.
     *
     * @param array $parsed
     *
     * @return $this
     */
    protected function setParsed(array $parsed): self
    {
        $this->parsed = $parsed;

        return $this;
    }

    /**
     * Get array with parsed data.
     *
     * @return array
     */
    public function getParsed(): array
    {
        return $this->parsed;
    }

    /**
     * Check if handler has all the necessary data assigned.
     *
     * @return bool
     */
    public function hasData(): bool
    {
        return $this->getQuery()->hasData() && ($this->getProvider() instanceof ProviderAbstract);
    }

    /**
     * Split raw data response into array by newline.
     *
     * @param string|null $raw Raw response from whois server
     *
     * @return string[]
     */
    public function splitRows($raw = null): array
    {
        if (null === $raw) {
            $raw = $this->getRaw();
        }

        // Line ending could be \r\n, \r, \n
        return \preg_split('/(\r\n|[\r\n])/', $raw);
    }

    /**
     * Try to split row into key => value array.
     *
     * @param string   $row           Line to parse
     * @param string[] $splitBy       Regexp for splitting the line. Method only looks for the first occurence of regexp
     * @param string[] $ignorePattern Don't parse rows which match the given expression, just return false
     *
     * @return array|false Return key => value array if regex found, or array with just 1 element otherwise
     */
    public function splitRow($row, array $splitBy = [], array $ignorePattern = [])
    {
        /*
         * TODO: Trim row's custom symbols (See .JP)
         */

        // If ignorePrefix is not empty and row matches it - return false
        if (!\count($ignorePattern)) {
            $ignorePattern = $this->patternComment;
        }
        foreach ($ignorePattern as $pattern) {
            if (\preg_match($pattern, $row)) {
                return false;
            }
        }

        $row = \trim($row);

        if (!\count($splitBy)) {
            $splitBy = $this->patternRowSeparator;
        }
        $parts = [];
        foreach ($splitBy as $separator) {
            $parts = \preg_split($separator, $row, 2);
            if (2 === \count($parts)) {
                $parts[1] = \trim($parts[1]);
                // If string was split by two parts - return immediately
                // Otherwise try another patterns
                return $parts;
            }
        }

        return $parts;
    }

    /**
     * Extract unix timestamp from the defined string.
     *
     * @param string $date Date
     *
     * @return int|false Unix timestamp
     */
    protected function parseDate($date)
    {
        $result = false;
        if (null === $this->dateFormat) {
            $result = \strtotime($date);
        } elseif (\count($this->dateFormat)) {
            foreach ($this->dateFormat as $format) {
                if ($dateTime = \DateTime::createFromFormat($format, $date)) {
                    $result = $dateTime->format('U');
                    break;
                }
            }
        }

        return $result;
    }

    /**
     * Try to extract the date from the given key and value.
     *
     * @param string   $row          Line from raw whois response
     * @param string[] $patterns     Array with patterns for matching the $key
     * @param string[] $antiPatterns Array with patterns which $key must not match
     *
     * @return false|int Unix timestamp
     */
    protected function extractDate($row, array $patterns, array $antiPatterns = [])
    {
        $result = false;

        foreach ($antiPatterns as $ap) {
            if (\preg_match($ap, $row)) {
                $result = false;

                return $result;
            }
        }

        $parts = $this->splitRow($row);
        if (2 === \count($parts)) {
            [$key, $value] = $parts;
        } else {
            return false;
        }

        foreach ($patterns as $pattern) {
            if (\preg_match($pattern, $key) && $time = $this->parseDate($value)) {
                $result = $time;
                break;
            }
        }

        return $result;
    }

    /**
     * Try to extract `registered`, `expires` and `updated` dates from the rows.
     *
     * @param string[] $rows Rows in key => value format
     *
     * @return string[]
     */
    protected function extractDates(array $rows): array
    {
        $dates = ['expires' => false, 'registered' => false, 'updated' => false];

        foreach ($rows as $row) {
            // Registration date
            if (!$dates['registered']) {
                $dates['registered'] = $this->extractDate($row, $this->patternRegistered) ?: $dates['registered'];
            }
            // Expiration date
            if (!$dates['expires']) {
                $dates['expires'] = $this->extractDate($row, $this->patternExpires) ?: $dates['expires'];
            }
            // Updated date
            if (!$dates['updated']) {
                $dates['updated'] = $this->extractDate($row, $this->patternUpdated, ['/>>>/i']) ?: $dates['updated'];
            }
        }

        return $dates;
    }

    /**
     * Try to parse response into key => value array
     * WARNING: This is very dirty solution, since multiple keys will override each other.
     *
     * @param string[] $rows
     *
     * @return array
     */
    protected function extractKeyValue(array $rows): array
    {
        $keyValue = [];
        foreach ($rows as $row) {
            $parts = $this->splitRow($row);
            if (false !== $parts && 2 === \count($parts)) {
                $keyValue[$parts[0]] = $parts[1];
            }
        }

        return $keyValue;
    }

    /**
     * Perform parsing and set necessary properties.
     *
     * @return array
     */
    protected function parse(): array
    {
        $rows = $this->splitRows();
        $this->setRows($rows);

        $parsed = [];
        $parsed['dates'] = $this->extractDates($rows);
        $parsed['keyValue'] = $this->extractKeyValue($rows);

        $this->setParsed($parsed);

        return $this->getParsed();
    }

    /**
     * Perform a lookup of defined query.
     *
     * @return Response
     *
     * @throws \InvalidArgumentException
     */
    public function lookup(): Response
    {
        if (!$this->hasData()) {
            throw new \InvalidArgumentException('Handler doesn\'t have query or provider set');
        }

        // Get raw response from provider
        $raw = $this->getProvider()->lookup();
        $this->setRaw($raw);

        // Set Response raw fields
        $response = new Response($this->getQuery());
        $response->setRaw($raw);

        $parsed = $this->parse();

        $response->setParsed($parsed);

        return $response;
    }
}

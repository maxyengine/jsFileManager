<?php

namespace Nrg\Http\Value;

/**
 * Trait HttpMessage.
 *
 * HTTP message implementation.
 */
trait HttpMessage
{
    /**
     * HTTP protocol version.
     *
     * @var string
     */
    private $protocolVersion = '1.1';

    /**
     * HTTP headers.
     *
     * @var array
     */
    private $headers = [];

    /**
     * HTTP headers original names.
     *
     * @var array
     */
    private $headerOriginalNames = [];

    /**
     * HTTP message body.
     *
     * @var null|mixed
     */
    private $body;

    /**
     * @return string
     */
    public function getProtocolVersion(): string
    {
        return $this->protocolVersion;
    }

    /**
     * @param string $version
     *
     * @return $this
     */
    public function setProtocolVersion(string $version): self
    {
        $this->protocolVersion = $version;

        return $this;
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @param $name
     *
     * @return bool
     */
    public function hasHeader($name): bool
    {
        return array_key_exists(strtolower($name), $this->headerOriginalNames);
    }

    /**
     * @param string $name
     *
     * @return array
     */
    public function getHeader(string $name): array
    {
        if (!$this->hasHeader($name)) {
            return [];
        }

        $originalName = $this->headerOriginalNames[strtolower($name)];

        return $this->headers[$originalName];
    }

    /**
     * Returns values separated by comma.
     *
     * @param string $name
     *
     * @return string
     */
    public function getHeaderLine(string $name): string
    {
        $header = $this->getHeader($name);

        return empty($header) ? '' : implode(',', $header);
    }

    /**
     * @param string       $name
     * @param array|string $value
     *
     * @return $this
     */
    public function setHeader(string $name, $value): self
    {
        $this->unsetHeader($name);

        if (!is_array($value)) {
            $value = [(string) $value];
        }
        $this->headerOriginalNames[strtolower($name)] = $name;
        $this->headers[$name] = $value;

        return $this;
    }

    /**
     * @param array $headers
     *
     * @return self
     */
    public function setHeaders(array $headers): self
    {
        foreach ($headers as $name => $value) {
            $this->setHeader($name, $value);
        }

        return $this;
    }

    /**
     * @param string       $name
     * @param array|string $value
     *
     * @return $this
     */
    public function setAddedHeader(string $name, $value): self
    {
        if ($this->hasHeader($name)) {
            if (!is_array($value)) {
                $value = [$value];
            }
            $this->setHeader($name, array_merge($this->getHeader($name), $value));
        } else {
            $this->setHeader($name, $value);
        }

        return $this;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function unsetHeader(string $name): self
    {
        if ($this->hasHeader($name)) {
            $lowCaseName = strtolower($name);
            $originalName = $this->headerOriginalNames[$lowCaseName];
            unset($this->headers[$originalName], $this->headerOriginalNames[$lowCaseName]);
        }

        return $this;
    }

    /**
     * @param string $name
     * @param string $needle
     *
     * @return bool
     */
    public function containsInHeader(string $name, string $needle): bool
    {
        foreach ($this->getHeader($name) as $value) {
            if (false !== strpos($value, $needle)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return null|mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param $body
     *
     * @return $this
     */
    public function setBody($body): self
    {
        $this->body = $body;

        return $this;
    }
}

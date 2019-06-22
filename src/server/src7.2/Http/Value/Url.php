<?php

namespace Nrg\Http\Value;

use InvalidArgumentException;
use Nrg\Utility\ArrayHelper;

/**
 * Class Url.
 *
 * HTTP Url implementation.
 */
class Url
{
    /**
     * @var null|string
     */
    private $scheme;

    /**
     * @var null|string
     */
    private $user;

    /**
     * @var null|string
     */
    private $password;

    /**
     * @var null|string
     */
    private $host;

    /**
     * @var null|int
     */
    private $port;

    /**
     * @var null|string
     */
    private $basePath;

    /**
     * @var null|string
     */
    private $path;

    /**
     * @var null|string
     */
    private $query;

    /**
     * @var null|string
     */
    private $fragment;

    /**
     * @var null|string
     */
    private $href;

    /**
     * @var null|string
     */
    private $hrefBeforeSearch;

    /**
     * Url constructor.
     *
     * @param string $url
     * @param null|string $basePath
     */
    public function __construct(string $url, string $basePath = null)
    {
        $partials = parse_url($url);

        $this->scheme = isset($partials['scheme']) ? $partials['scheme'] : null;
        $this->user = isset($partials['user']) ? $partials['user'] : null;
        $this->password = isset($partials['pass']) ? $partials['pass'] : null;
        $this->host = isset($partials['host']) ? $partials['host'] : null;
        $this->port = isset($partials['port']) ? (int)$partials['port'] : null;
        $this->query = isset($partials['query']) ? $partials['query'] : null;
        $this->fragment = isset($partials['fragment']) ? $partials['fragment'] : null;

        $this->initPath($partials['path'] ?? null, null === $basePath ? null : trim($basePath, '/'));
    }

    /**
     * @return string
     */
    public function __toString()
    {
        if (null !== $this->href) {
            return $this->href;
        }

        $this->href = $this->getHrefBeforeSearch();

        if (null !== $this->query) {
            $this->href .= '?'.$this->query;
        }
        if (null !== $this->fragment) {
            $this->href .= '#'.$this->fragment;
        }

        return $this->href;
    }

    /**
     * @return null|string
     */
    public function getScheme()
    {
        return $this->scheme;
    }

    /**
     * @param string $scheme
     *
     * @return $this
     */
    public function setScheme(string $scheme): self
    {
        $this->scheme = $scheme;
        $this->reset();

        return $this;
    }

    /**
     * @return null|string
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param string $user
     *
     * @return $this
     */
    public function setUser(string $user): self
    {
        $this->user = $user;
        $this->reset();

        return $this;
    }

    /**
     * @return null|string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     *
     * @return $this
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;
        $this->reset();

        return $this;
    }

    /**
     * @return null|string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @param string $host
     *
     * @return $this
     */
    public function setHost(string $host): self
    {
        $this->host = $host;
        $this->reset();

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
     * @param int $port
     *
     * @return $this
     */
    public function setPort(int $port): self
    {
        $this->port = $port;
        $this->reset();

        return $this;
    }

    /**
     * @return null|string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     *
     * @return $this
     */
    public function setPath(string $path): self
    {
        $this->path = '/'.trim($path, '/');
        $this->reset();

        return $this;
    }

    /**
     * @return Url
     */
    public function makeClone(): Url
    {
        return new static($this, $this->basePath);
    }

    /**
     * @return null|string
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @param string $query
     *
     * @return $this
     */
    public function setQuery($query): self
    {
        $this->query = $query;
        $this->reset();

        return $this;
    }

    /**
     * @return array
     */
    public function getQueryParams()
    {
        if (null === $this->query) {
            return [];
        }

        parse_str($this->query, $params);

        return $params;
    }

    /**
     * @param array $params
     *
     * @return $this
     */
    public function setQueryParams(array $params): self
    {
        $this->query = empty($params) ? null : http_build_query($params);
        $this->reset();

        return $this;
    }

    /**
     * @param string $name
     * @param null $default
     *
     * @return mixed|null
     */
    public function getQueryParam(string $name, $default = null)
    {
        $params = $this->getQueryParams();

        return $params[$name] ?? $default;
    }

    /**
     * @return null|string
     */
    public function getFragment()
    {
        return $this->fragment;
    }

    /**
     * @param string $fragment
     *
     * @return $this
     */
    public function setFragment(string $fragment): self
    {
        $this->fragment = $fragment;
        $this->reset();

        return $this;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return (string)$this;
    }

    public function contains(Url $url): bool
    {
        return $this->getHrefBeforeSearch() === $url->getHrefBeforeSearch() &&
            ArrayHelper::contains($url->getQueryParams(), $this->getQueryParams()) &&
            $this->getFragment() === $url->getFragment();
    }

    /**
     * @return null|string
     */
    public function getHrefBeforeSearch(): ?string
    {
        if (null !== $this->hrefBeforeSearch) {
            return $this->hrefBeforeSearch;
        }

        $this->hrefBeforeSearch = '';

        if (null !== $this->scheme) {
            $this->hrefBeforeSearch .= $this->scheme.'://';
        }
        if (null !== $this->user) {
            $this->hrefBeforeSearch .= $this->user;
        }
        if (null !== $this->password && null !== $this->user) {
            $this->hrefBeforeSearch .= ':'.$this->password;
        }
        if (null !== $this->host) {
            $this->hrefBeforeSearch .= null === $this->user ? $this->host : '@'.$this->host;
        }
        if (null !== $this->port && null !== $this->host) {
            $this->hrefBeforeSearch .= ':'.$this->port;
        }
        $this->hrefBeforeSearch .= rtrim($this->basePath, '/');
        $this->hrefBeforeSearch .= $this->path;

        return $this->hrefBeforeSearch;
    }

    private function reset()
    {
        $this->href = null;
        $this->hrefBeforeSearch = null;
    }

    private function initPath(?string $path, ?string $basePath)
    {
        $path = null === $path ? '/' : '/'.trim($path, '/');

        if (null === $basePath) {
            $this->path = $path;

            return;
        }

        $this->basePath = '/'.$basePath;
        if (substr($path, 0, strlen($this->basePath)) !== $this->basePath) {
            throw new InvalidArgumentException('Invalid basePath, it must be part of the path');
        }

        $path = substr($path, strlen($this->basePath));
        $this->path = empty($path) ? '/' : $path;
    }
}

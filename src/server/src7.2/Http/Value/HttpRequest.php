<?php

namespace Nrg\Http\Value;

/**
 * Class HttpRequest.
 *
 * HTTP request implementation.
 */
class HttpRequest
{
    use HttpMessage;

    /**
     * @var Url
     */
    private $url;

    /**
     * @var string
     */
    private $method;

    /**
     * @var array
     */
    private $cookies = [];

    /**
     * @var array
     */
    private $queryParams = [];

    /**
     * @var null|mixed
     */
    private $bodyParams;

    /**
     * @var array
     */
    private $uploadedFiles = [];

    /**
     * @return Url
     */
    public function getUrl(): Url
    {
        return $this->url;
    }

    /**
     * @param Url $url
     *
     * @return $this
     */
    public function setUrl(Url $url): self
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @param string $method
     *
     * @return $this
     */
    public function setMethod(string $method): self
    {
        $this->method = $method;

        return $this;
    }

    /**
     * @return array
     */
    public function getCookies(): array
    {
        return $this->cookies;
    }

    /**
     * @param array $cookies
     *
     * @return $this
     */
    public function setCookies(array $cookies): self
    {
        $this->cookies = $cookies;

        return $this;
    }

    /**
     * @return array
     */
    public function getQueryParams(): array
    {
        return $this->queryParams;
    }

    /**
     * @param string      $name
     * @param null|string $default
     *
     * @return null|string
     */
    public function getQueryParam(string $name, string $default = null)
    {
        return $this->queryParams[$name] ?? $default;
    }

    /**
     * @param array $queryParams
     *
     * @return $this
     */
    public function setQueryParams(array $queryParams): self
    {
        $this->queryParams = $queryParams;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBodyParams()
    {
        return $this->bodyParams;
    }

    /**
     * @param mixed $bodyParams
     *
     * @return $this
     */
    public function setBodyParams($bodyParams): self
    {
        $this->bodyParams = $bodyParams;

        return $this;
    }

    /**
     * @param $name
     * @param null|mixed $default
     *
     * @return null|string
     */
    public function getBodyParam($name, $default = null)
    {
        return $this->bodyParams[$name] ?? $default;
    }

    /**
     * @param string $name
     * @param $value
     *
     * @return $this
     */
    public function setBodyParam(string $name, $value): self
    {
        $this->bodyParams[$name] = $value;

        return $this;
    }

    /**
     * @return UploadedFile[]
     */
    public function getUploadedFiles(): array
    {
        return $this->uploadedFiles;
    }

    /**
     * @param string    $name
     *
     * @return UploadedFile|null
     */
    public function getUploadedFile(string $name): ?UploadedFile
    {
        return $this->uploadedFiles[$name] ?? null;
    }

    /**
     * @param UploadedFile[] $uploadedFiles
     *
     * @return $this
     */
    public function setUploadedFiles(array $uploadedFiles)
    {
        $this->uploadedFiles = $uploadedFiles;

        return $this;
    }
}

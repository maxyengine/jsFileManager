<?php

namespace Nrg\Http\Value;

/**
 * Class CgiRequest.
 *
 * HTTP request implementation.
 */
class CgiRequest extends HttpRequest
{
    /**
     * CgiHTTPHttpRequest constructor.
     */
    public function __construct()
    {
        $this
            ->setProtocolVersion(substr($_SERVER['SERVER_PROTOCOL'], strpos($_SERVER['SERVER_PROTOCOL'], '/') + 1))
            ->setUrl(new CgiUrl())
            ->setMethod($_SERVER['REQUEST_METHOD'])
            ->setCookies($_COOKIE)
            ->setQueryParams($_GET)
            ->setBody(file_get_contents('php://input'))
            ->setBodyParams($_POST)
            ->setUploadedFiles($this->convertToUploadedFiles($_FILES));

        foreach (getallheaders() as $name => $value) {
            $this->setHeader($name, $value);
        }
    }

    /**
     * Converts each of array items to UploadedFile.
     *
     * @param array $files
     *
     * @return UploadedFile[]
     */
    private function convertToUploadedFiles(array $files): array
    {
        $uploadedFiles = [];
        foreach ($files as $key => $file) {
            $uploadedFiles[$key] = new UploadedFile(
                $file['name'],
                $file['type'],
                $file['tmp_name'],
                $file['error'],
                $file['size']
            );
        }

        return $uploadedFiles;
    }
}

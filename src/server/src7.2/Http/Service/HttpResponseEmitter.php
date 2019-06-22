<?php

namespace Nrg\Http\Service;

use Nrg\Http\Abstraction\ResponseEmitter;
use Nrg\Http\Value\HttpResponse;

/**
 * Class HttpResponseEmitter
 *
 * Emits the HTTP response.
 */
class HttpResponseEmitter implements ResponseEmitter
{
    /**
     * Emits the HTTP response.
     *
     * @param HttpResponse $response
     * @param bool $terminate
     */
    public function emit(HttpResponse $response, $terminate = false): void
    {
        header(
            sprintf(
                'HTTP/%s %d %s',
                $response->getProtocolVersion(),
                $response->getStatus()->getCode(),
                $response->getStatus()->getReasonPhrase()
            )
        );

        foreach ($response->getHeaders() as $name => $values) {
            $name = str_replace(' ', '-', ucwords(str_replace('-', ' ', $name)));
            foreach ($values as $value) {
                header(sprintf('%s: %s', $name, $value), false);
            }
        }

        echo $response->getBody();

        if ($terminate) {
            exit;
        }
    }
}

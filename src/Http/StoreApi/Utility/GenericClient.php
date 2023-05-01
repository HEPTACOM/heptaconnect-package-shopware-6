<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Utility;

use Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Action\Contract\Generic\GenericActionInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Action\Contract\Generic\GenericPayload;

/**
 * Facade to send common HTTP methods to prototype request or build not yet existing actions.
 * If you want to intercept a process, use the action services.
 */
final class GenericClient
{
    private GenericActionInterface $generic;

    public function __construct(GenericActionInterface $generic)
    {
        $this->generic = $generic;
    }

    /**
     * Send an authenticated POST request to the given path.
     *
     * @throws \Throwable
     */
    public function post(string $path, ?array $body = [], ?array $query = [], array $headers = []): ?array
    {
        return $this->generic->sendGenericRequest(
            (new GenericPayload($path, 'POST'))
                ->withBody($body)
                ->withQueryParameters($query)
                ->withHeaders($headers)
        )->getBody();
    }

    /**
     * Send an authenticated GET request to the given path.
     *
     * @throws \Throwable
     */
    public function get(string $path, ?array $query = [], array $headers = []): ?array
    {
        return $this->generic->sendGenericRequest(
            (new GenericPayload($path, 'GET'))
                ->withQueryParameters($query)
                ->withHeaders($headers)
        )->getBody();
    }

    /**
     * Send an authenticated PATCH request to the given path.
     *
     * @throws \Throwable
     */
    public function patch(string $path, ?array $body = [], ?array $query = [], array $headers = []): ?array
    {
        return $this->generic->sendGenericRequest(
            (new GenericPayload($path, 'PATCH'))
                ->withBody($body)
                ->withQueryParameters($query)
                ->withHeaders($headers)
        )->getBody();
    }

    /**
     * Send an authenticated PUT request to the given path.
     *
     * @throws \Throwable
     */
    public function put(string $path, ?array $body = [], ?array $query = [], array $headers = []): ?array
    {
        return $this->generic->sendGenericRequest(
            (new GenericPayload($path, 'PUT'))
                ->withBody($body)
                ->withQueryParameters($query)
                ->withHeaders($headers)
        )->getBody();
    }

    /**
     * Send an authenticated DELETE request to the given path.
     *
     * @throws \Throwable
     */
    public function delete(string $path, ?array $query = [], array $headers = []): ?array
    {
        return $this->generic->sendGenericRequest(
            (new GenericPayload($path, 'DELETE'))
                ->withQueryParameters($query)
                ->withHeaders($headers)
        )->getBody();
    }
}

<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\Support\Action\Generic;

abstract class AbstractGenericClient
{
    /**
     * Send an authenticated POST request to the given path.
     *
     * @throws \Throwable
     */
    public function post(string $path, ?array $body = [], ?array $query = [], array $headers = []): ?array
    {
        return $this->sendGenericRequest(
            $this->generatePayload($path, 'POST')
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
        return $this->sendGenericRequest(
            $this->generatePayload($path, 'GET')
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
        return $this->sendGenericRequest(
            $this->generatePayload($path, 'PATCH')
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
        return $this->sendGenericRequest(
            $this->generatePayload($path, 'PUT')
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
        return $this->sendGenericRequest(
            $this->generatePayload($path, 'DELETE')
                ->withQueryParameters($query)
                ->withHeaders($headers)
        )->getBody();
    }

    abstract protected function generatePayload(string $path, string $method): AbstractGenericPayload;

    abstract protected function sendGenericRequest(AbstractGenericPayload $payload): AbstractGenericResult;
}

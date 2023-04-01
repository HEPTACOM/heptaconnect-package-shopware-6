<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Contract\AuthenticatedHttpClientInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Contract\AuthenticationStorageInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class AuthenticatedHttpClient implements AuthenticatedHttpClientInterface
{
    private ClientInterface $decorated;

    private AuthenticationStorageInterface $authenticationStorage;

    public function __construct(ClientInterface $decorated, AuthenticationStorageInterface $authenticationStorage)
    {
        $this->decorated = $decorated;
        $this->authenticationStorage = $authenticationStorage;
    }

    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        $response = $this->decorated->sendRequest($request->withAddedHeader(
            'Authorization',
            $this->authenticationStorage->getAuthorizationHeader()
        ));

        if ($response->getStatusCode() !== 401) {
            return $response;
        }

        $this->authenticationStorage->refresh();

        return $this->decorated->sendRequest($request->withAddedHeader(
            'Authorization',
            $this->authenticationStorage->getAuthorizationHeader()
        ));
    }
}

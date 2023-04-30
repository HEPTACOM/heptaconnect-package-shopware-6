<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Authentication;

use Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Authentication\Contract\AuthenticatedHttpClientInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Authentication\Contract\AuthenticationInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class AuthenticatedHttpClient implements AuthenticatedHttpClientInterface
{
    private ClientInterface $decorated;

    private AuthenticationInterface $authenticationStorage;

    public function __construct(ClientInterface $decorated, AuthenticationInterface $authenticationStorage)
    {
        $this->decorated = $decorated;
        $this->authenticationStorage = $authenticationStorage;
    }

    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        return $this->decorated->sendRequest($request->withAddedHeader(
            'sw-access-key',
            $this->authenticationStorage->getAccessKey()
        ));
    }
}

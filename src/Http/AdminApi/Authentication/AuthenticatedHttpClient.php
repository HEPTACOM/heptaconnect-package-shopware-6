<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Contract\AuthenticatedHttpClientInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Contract\AuthenticationInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Exception\AuthenticationFailed;
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
        try {
            $response = $this->decorated->sendRequest($request->withAddedHeader(
                'Authorization',
                $this->authenticationStorage->getAuthorizationHeader()
            ));

            if ($response->getStatusCode() !== 401) {
                return $response;
            }
        } catch (AuthenticationFailed $authenticationFailed) {
            // this is ok to happen, we just try to refresh and try again
        }

        $this->authenticationStorage->refresh();

        return $this->decorated->sendRequest($request->withAddedHeader(
            'Authorization',
            $this->authenticationStorage->getAuthorizationHeader()
        ));
    }
}

<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Contract\ApiConfigurationStorageInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Contract\AuthenticationInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Exception\AuthenticationFailed;
use Heptacom\HeptaConnect\Package\Shopware6\Support\JsonStreamUtility;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;

final class PortalNodeStorageAuthentication implements AuthenticationInterface
{
    private CacheInterface $portalStorage;

    private JsonStreamUtility $jsonStreamUtility;

    private RequestFactoryInterface $requestFactory;

    private ClientInterface $client;

    private ApiConfigurationStorageInterface $apiConfigurationStorage;

    public function __construct(
        CacheInterface $portalStorage,
        JsonStreamUtility $jsonStreamUtility,
        RequestFactoryInterface $requestFactory,
        ClientInterface $client,
        ApiConfigurationStorageInterface $apiConfigurationStorage
    ) {
        $this->portalStorage = $portalStorage;
        $this->jsonStreamUtility = $jsonStreamUtility;
        $this->requestFactory = $requestFactory;
        $this->client = $client;
        $this->apiConfigurationStorage = $apiConfigurationStorage;
    }

    public function refresh(): void
    {
        try {
            $configuration = $this->apiConfigurationStorage->getConfiguration();
        } catch (\Throwable $exception) {
            throw new AuthenticationFailed(1680350610, $exception);
        }

        try {
            $body = $this->jsonStreamUtility->fromPayloadToStream($this->createTokenRequestPayload($configuration));
        } catch (\JsonException $exception) {
            throw new AuthenticationFailed(
                1680350611,
                new \UnexpectedValueException('Invalid API configuration', 0, $exception)
            );
        }

        $oauthRequest = $this->requestFactory
            ->createRequest('POST', $configuration->getUrl() . '/oauth/token')
            ->withAddedHeader('Content-Type', 'application/json')
            ->withAddedHeader('Accept', 'application/json')
            ->withBody($body);

        try {
            $oauthResponse = $this->client->sendRequest($oauthRequest);
        } catch (\Throwable $exception) {
            throw new AuthenticationFailed(1680350612, $exception);
        }

        if ($oauthResponse->getStatusCode() !== 200) {
            throw new AuthenticationFailed(
                1680350613,
                new \UnexpectedValueException('Authentication response is not 200' . \PHP_EOL . $oauthResponse->getBody())
            );
        }

        try {
            $responseData = $this->jsonStreamUtility->fromStreamToPayload($oauthResponse->getBody());
        } catch (\Throwable $exception) {
            throw new AuthenticationFailed(
                1680350614,
                new \UnexpectedValueException('Response is not a JSON response' . \PHP_EOL . $oauthResponse->getBody())
            );
        }

        try {
            $tokenData = [
                'oauth.token_type' => $responseData['token_type'],
                'oauth.expires_in' => $responseData['expires_in'],
                'oauth.access_token' => $responseData['access_token'],
            ];

            if (!$this->portalStorage->setMultiple($tokenData, (int) $responseData['expires_in'])) {
                throw new \RuntimeException('Failed to write into the portal node storage');
            }
        } catch (InvalidArgumentException|\Throwable $exception) {
            throw new AuthenticationFailed(1680350615, $exception);
        }
    }

    public function getAuthorizationHeader(): string
    {
        try {
            $oauthData = \iterable_to_array($this->portalStorage->getMultiple([
                'oauth.token_type',
                'oauth.access_token',
            ]));
        } catch (InvalidArgumentException $exception) {
            throw new AuthenticationFailed(1680350600, $exception);
        }

        if (!\is_string($oauthData['oauth.token_type'])) {
            throw new AuthenticationFailed(1680350601, new \UnexpectedValueException('Missing oauth.token_type'));
        }

        if (!\is_string($oauthData['oauth.access_token'])) {
            throw new AuthenticationFailed(1680350602, new \UnexpectedValueException('Missing oauth.access_token'));
        }

        return $oauthData['oauth.token_type'] . ' ' . $oauthData['oauth.access_token'];
    }

    /**
     * @throws AuthenticationFailed
     */
    private function createTokenRequestPayload(ApiConfiguration $configuration): array
    {
        $grantType = $configuration->getGrantType();

        if ($grantType === 'client_credentials') {
            return [
                'grant_type' => $grantType,
                'client_id' => $configuration->getUsername(),
                'client_secret' => $configuration->getSecret(),
                'scopes' => \implode(' ', $configuration->getScopes()),
            ];
        }

        if ($grantType === 'password') {
            return [
                'grant_type' => $grantType,
                'client_id' => 'administration',
                'username' => $configuration->getUsername(),
                'password' => $configuration->getSecret(),
                'scopes' => \implode(' ', $configuration->getScopes()),
            ];
        }

        throw new AuthenticationFailed(1680350620, new \UnexpectedValueException(
            \sprintf('Unsupported grant_type: "%s". Supported values "client_credentials" and "password"', $grantType),
        ));
    }
}

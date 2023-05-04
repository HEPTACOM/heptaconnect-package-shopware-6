<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Action\Support;

use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Contract\ErrorHandlerInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Action\Contract\ContextTokenAwareInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Action\Contract\ContextTokenRequiredInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Authentication\Contract\ApiConfigurationStorageInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Authentication\Contract\AuthenticatedHttpClientInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\Support\AbstractShopwareClientUtils;
use Heptacom\HeptaConnect\Package\Shopware6\Support\JsonStreamUtility;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;

/**
 * A class holding dependencies and utility methods to easily create JSON requests and parse JSON responses with meaningful exceptions.
 */
final class ActionClientUtils extends AbstractShopwareClientUtils
{
    private ApiConfigurationStorageInterface $apiConfigurationStorage;

    public function __construct(
        AuthenticatedHttpClientInterface $client,
        RequestFactoryInterface $requestFactory,
        ApiConfigurationStorageInterface $apiConfigurationStorage,
        JsonStreamUtility $jsonStreamUtility,
        ErrorHandlerInterface $errorHandler
    ) {
        parent::__construct($client, $requestFactory, $jsonStreamUtility, $errorHandler);
        $this->apiConfigurationStorage = $apiConfigurationStorage;
    }

    /**
     * @param ContextTokenAwareInterface|ContextTokenRequiredInterface $contextTokenAware
     */
    public function addContextToken(RequestInterface $request, $contextTokenAware): RequestInterface
    {
        $contextToken = null;

        if ($contextTokenAware instanceof ContextTokenAwareInterface) {
            $contextToken = $contextTokenAware->getContextToken();
        }

        if ($contextTokenAware instanceof ContextTokenRequiredInterface) {
            $contextToken = $contextTokenAware->getContextToken();
        }

        if ($contextToken !== null) {
            $request = $request->withHeader('sw-context-token', $contextToken);
        }

        return $request;
    }

    protected function getBaseUrl(): string
    {
        return $this->apiConfigurationStorage->getConfiguration()->getUrl();
    }
}

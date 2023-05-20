<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Test\Support\Package\AdminApi;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\InfoVersion\InfoVersionParams;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\InfoVersionAction;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\ApiConfiguration;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Utility\DependencyInjection\AdminApiFactory;
use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Contract\JsonResponseValidatorCollection;
use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Contract\JsonResponseValidatorInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Test\Support\TestBootstrapper;
use Heptacom\HeptaConnect\Package\Shopware6\Utility\DependencyInjection\BaseFactory;
use Heptacom\HeptaConnect\Package\Shopware6\Utility\DependencyInjection\SyntheticServiceContainer;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class Factory
{
    private static ?string $shopwareVersion = null;

    public static function getShopwareVersion(): string
    {
        $result = static::$shopwareVersion;

        if ($result === null) {
            $shopwareVersion = (new InfoVersionAction(self::createAdminApiFactory()->getActionClientUtils()))
                ->getVersion(new InfoVersionParams())
                ->getVersion();
            $result = $shopwareVersion;
            static::$shopwareVersion = $result;
        }

        return $result;
    }

    public static function createAdminApiFactory(array $services = []): AdminApiFactory
    {
        $apiConfiguration = self::createApiConfiguration();
        $emptyStoreApiFactory = new AdminApiFactory($apiConfiguration);
        $validators = new JsonResponseValidatorCollection($emptyStoreApiFactory->getJsonResponseValidatorCollection());
        $validators->push([
            new class() implements JsonResponseValidatorInterface {
                public function validate(array $body, ?array $error, RequestInterface $request, ResponseInterface $response): void
                {
                    if ($error !== null) {
                        throw new \RuntimeException('Found error, that is not yet covered by a validator');
                    }
                }
            },
        ]);

        return new AdminApiFactory($apiConfiguration, new BaseFactory(new SyntheticServiceContainer([
            JsonResponseValidatorCollection::class . '.admin_api' => $validators,
        ] + $services)));
    }

    public static function createApiConfiguration(): ApiConfiguration
    {
        return new ApiConfiguration(
            'password',
            TestBootstrapper::instance()->getAdminApiUrl(),
            TestBootstrapper::instance()->getAdminApiUsername(),
            TestBootstrapper::instance()->getAdminApiPassword(),
            ['write'],
        );
    }
}

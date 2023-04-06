<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Utility\DependencyInjection;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Contract\SyncAction\SyncPayloadInterceptorInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Tag every sync payload interceptor to collect them later in the collection service.
 * Expects to be run after @see \Symfony\Component\DependencyInjection\Compiler\ResolveClassPass
 * and before @see \Symfony\Component\DependencyInjection\Compiler\ResolveTaggedIteratorArgumentPass
 */
final class AdminApiSyncPayloadInterceptorRegistrationCompilerPass implements CompilerPassInterface
{
    /**
     * The tag, that is used to identify the services.
     */
    public const TAG_NAME = 'heptaconnect.package.shopware6.admin_api.sync_payload_interceptor';

    /**
     * The suggested pass type, to use, when adding the compiler pass.
     */
    public const PASS_TYPE = PassConfig::TYPE_BEFORE_OPTIMIZATION;

    /**
     * The suggested pass priority, to use, when adding the compiler pass.
     */
    public const PASS_PRIORITY = 0;

    public function process(ContainerBuilder $container): void
    {
        foreach ($container->getDefinitions() as $definition) {
            $class = $definition->getClass();

            if ($class === null) {
                continue;
            }

            if ($definition->hasTag(self::TAG_NAME)) {
                continue;
            }

            if (!\is_a($class, SyncPayloadInterceptorInterface::class, true)) {
                continue;
            }

            $definition->addTag(self::TAG_NAME);
        }
    }
}

<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Test\Support;

final class TestBootstrapper
{
    private static ?self $instance = null;

    private ?string $adminApiUrl = null;

    private ?string $adminApiUsername = null;

    private ?string $adminApiPassword = null;

    private function __construct()
    {
    }

    public static function instance(): self
    {
        $result = static::$instance;
        $result ??= new TestBootstrapper();
        static::$instance = $result;

        return $result;
    }

    public function bootstrap(): self
    {
        $this->adminApiUrl = $this->env('TEST_ADMIN_API_URL');
        $this->adminApiUsername = $this->env('TEST_ADMIN_API_USERNAME');
        $this->adminApiPassword = $this->env('TEST_ADMIN_API_PASSWORD');

        return $this;
    }

    public function getAdminApiUrl(): ?string
    {
        return $this->adminApiUrl;
    }

    public function getAdminApiUsername(): ?string
    {
        return $this->adminApiUsername;
    }

    public function getAdminApiPassword(): ?string
    {
        return $this->adminApiPassword;
    }

    private function env(string $key): ?string
    {
        $result = \getenv($key);

        if ($result === false) {
            return null;
        }

        return (string) $result;
    }
}

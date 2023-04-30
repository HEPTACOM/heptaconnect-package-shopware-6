<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Test\Support;

final class TestBootstrapper
{
    private static ?self $instance = null;

    private ?string $adminApiUrl = null;

    private ?string $adminApiUsername = null;

    private ?string $adminApiPassword = null;

    private ?string $storeApiUrl = null;

    private ?string $storeApiAccessKey = null;

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
        $this->storeApiUrl = $this->env('TEST_STORE_API_URL');
        $this->storeApiAccessKey = $this->env('TEST_STORE_API_ACCESS_KEY');

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

    public function getStoreApiUrl(): ?string
    {
        return $this->storeApiUrl;
    }

    public function getStoreApiAccessKey(): ?string
    {
        return $this->storeApiAccessKey;
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

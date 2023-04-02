# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to a variation of [Semantic Versioning](https://semver.org/spec/v2.0.0.html).
The version numbers are structured like `GENERATION.MAJOR.MINOR.PATCH`:

* `GENERATION` version when concepts and APIs are abandoned, but brand and project name stay the same,
* `MAJOR` version when you make incompatible API changes and provide an upgrade path,
* `MINOR` version when you add functionality in a backwards compatible manner, and
* `PATCH` version when you make backwards compatible bug fixes.

## [Unreleased]

### Added

- Add composer dependency `heptacom/heptaconnect-portal-base: >=0.9.4 <0.10` to make use of HEPTAconnect portal and package tools
- Add composer dependency `psr/http-client: ^1.0` and `psr/http-message: ^1.0` as HTTP request and responses needs to be handled
- Add composer dependency `ext-json: *` as JSON requests and responses needs to be sent and parsed
- Add contract `\Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Contract\ApiConfigurationStorageInterface` to provide `\Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\ApiConfiguration`, that is used to identify and authenticate against a Shopware API
- Add utility `\Heptacom\HeptaConnect\Package\Shopware6\Support\JsonStreamUtility` to unify JSON handling
- Add exception code `1680371700` to `\Heptacom\HeptaConnect\Package\Shopware6\Support\JsonStreamUtility::fromPayloadToStream` when non-JSON-specific encoding issues occur
- Add exception code `1680371701` to `\Heptacom\HeptaConnect\Package\Shopware6\Support\JsonStreamUtility::fromStreamToPayload` when the decoded JSON is not a PHP array
- Add exception `\Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Exception\AuthenticationFailed` to identify issues on authenticating with the Shopware API
- Add contract `\Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Contract\AuthenticationStorageInterface` to recalculate and retrieve authentication information to communicate with Shopware 6 API
- Add default implementation `\Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\PortalNodeStorageAuthenticationStorage` for `\Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Contract\AuthenticationStorageInterface` to request new authentication token and store the token in the portal node storage to share it with other PHP instances
- Add implementation `\Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\MemoryAuthenticationStorageCache` as decorator for `\Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Contract\AuthenticationStorageInterface` to hold authentication information in-memory to reduce calls to any I/O dependant storage
- Add exception code `1680350600` to `\Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\PortalNodeStorageAuthenticationStorage::getAuthorizationHeader` when the token data could not be read from the portal node storage
- Add exception code `1680350601` to `\Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\PortalNodeStorageAuthenticationStorage::getAuthorizationHeader` when the token data is missing the token_type
- Add exception code `1680350602` to `\Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\PortalNodeStorageAuthenticationStorage::getAuthorizationHeader` when the token data is missing the access_token
- Add exception code `1680350610` to `\Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\PortalNodeStorageAuthenticationStorage::refresh` when the API configuration could not be loaded
- Add exception code `1680350611` to `\Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\PortalNodeStorageAuthenticationStorage::refresh` when the token request could not be created
- Add exception code `1680350612` to `\Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\PortalNodeStorageAuthenticationStorage::refresh` when the token request could not be sent
- Add exception code `1680350613` to `\Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\PortalNodeStorageAuthenticationStorage::refresh` when the token request received a non-OK response
- Add exception code `1680350614` to `\Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\PortalNodeStorageAuthenticationStorage::refresh` when the token response could not be parsed
- Add exception code `1680350615` to `\Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\PortalNodeStorageAuthenticationStorage::refresh` when the token data could not be stored in the portal node storage
- Add exception code `1680350620` to `\Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\PortalNodeStorageAuthenticationStorage` when the grant_type in the API configuration is not supported
- Add implementation `\Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\AuthenticatedHttpClient` for contract `\Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Contract\AuthenticatedHttpClientInterface` to automatically authorize requests using the `\Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Contract\AuthenticationStorageInterface`
- Add base class for exceptions `\Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\AbstractRequestException` that need a reference to a request
- Add exception `\Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\MalformedResponse` to identify issues with expected formats of an HTTP response
- Add contract `\Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Contract\ErrorHandlerInterface` to detect errors in a Shopware request cycle 
- Add implementation `\Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseErrorHandler` for contract `\Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Contract\ErrorHandlerInterface` to detect any errors in a JSON response using multiple `\Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Contract\JsonResponseValidatorInterface` detected by tag `heptaconnect.package.shopware6.json_response_validator`
- Add base class `\Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\AbstractActionClient` for services to work with Shopware action endpoints

### Changed

### Deprecated

### Removed

### Fixed

### Security

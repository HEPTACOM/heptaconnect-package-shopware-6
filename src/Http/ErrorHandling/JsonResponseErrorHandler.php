<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling;

use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Contract\ErrorHandlerInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Contract\JsonResponseValidatorInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Exception\JsonResponseValidationCollectionException;
use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Exception\MalformedResponse;
use Heptacom\HeptaConnect\Package\Shopware6\Support\JsonStreamUtility;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class JsonResponseErrorHandler implements ErrorHandlerInterface
{
    private JsonStreamUtility $jsonStreamUtility;

    /**
     * @var array<\Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Contract\JsonResponseValidatorInterface>
     */
    private iterable $validators;

    /**
     * @param iterable<\Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Contract\JsonResponseValidatorInterface> $validators
     */
    public function __construct(JsonStreamUtility $jsonStreamUtility, iterable $validators)
    {
        $this->jsonStreamUtility = $jsonStreamUtility;
        $this->validators = \iterable_to_array(\iterable_map(
            $validators,
            static fn (JsonResponseValidatorInterface $validator): JsonResponseValidatorInterface => $validator
        ));
    }

    public function throwException(RequestInterface $request, ResponseInterface $response): void
    {
        try {
            $data = $this->jsonStreamUtility->fromStreamToPayload($response->getBody());
        } catch (\JsonException $exception) {
            throw new MalformedResponse($request, $response, 0, $exception);
        }

        $errors = $this->collectErrors($data);
        $exceptions = [];

        // allow error checks for non-extractable errors
        if ($errors === []) {
            $errors[] = null;
        }

        /** @var array|null $error */
        foreach ($errors as $error) {
            try {
                foreach ($this->validators as $validator) {
                    $validator->validate($data, $error, $request, $response);
                }
            } catch (\Throwable $exception) {
                $exceptions[] = $exception;
            }
        }

        if ($exceptions !== []) {
            if (\count($exceptions) === 1) {
                throw current($exceptions);
            }

            throw new JsonResponseValidationCollectionException(
                $request,
                $response,
                $exceptions,
                'Found multiple exceptions',
                1680482000
            );
        }
    }

    private function collectErrors(array $response): array
    {
        $result = [];

        if (isset($response['errors'])) {
            $result = \array_merge($result, $response['errors']);
        }

        if (!isset($response['data'])) {
            return $result;
        }

        foreach ($response['data'] as $data) {
            foreach ($data['result'] ?? [] as $value) {
                if (!isset($value['errors'])) {
                    continue;
                }

                $result = \array_merge($result, $value['errors']);
            }
        }

        return $result;
    }
}

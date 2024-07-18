<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Services\Normalizers;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * NormalizerService class.
 *
 * This class implements the NormalizerInterface and provides functionality to normalize data.
 */
final readonly class DateNormalizer implements NormalizerInterface
{
    /**
     * Normalizes an DateTimeInterface object.
     *
     * @param mixed                         $object  the object to normalize
     * @param string|null                   $format  The format in which to normalize the object. (Optional)
     * @param array<string|object|bool|int> $context The normalization context. (Optional)
     *
     * @return array<string, mixed>|string|int|float|bool|\ArrayObject|null
     */
    public function normalize(mixed $object, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        return $object->format('Y-m-d H:i:s');
    }

    /**
     * Checks if the given data supports normalization.
     *
     * @param mixed               $data    the data to be checked for normalization support
     * @param string|null         $format  The format for which the normalization support needs to be checked. Defaults to null.
     * @param array<string,mixed> $context Additional context for the normalization. Defaults to an empty array.
     *
     * @return bool true if the data supports normalization, false otherwise
     */
    public function supportsNormalization($data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof \DateTimeInterface;
    }

    /**
     * Retrieves the supported types for a given format.
     *
     * @param string|null $format The format for which the supported types need to be retrieved. Defaults to null.
     *
     * @return array<string|bool|null> an array of supported types, where the keys represent the type names and the values represent their support status
     */
    public function getSupportedTypes(?string $format): array
    {
        return [\DateTimeInterface::class => true];
    }
}

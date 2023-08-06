<?php

namespace App\Serializer;

use App\Exception\ApiException;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ApiExceptionNormalizer implements NormalizerInterface
{
    private $debug;

    public function __construct(KernelInterface $kernel)
    {
        $this->debug = $kernel->isDebug();
    }

    public function normalize($object, string $format = null, array $context = []): array
    {
        $debug = $this->debug && ($context['debug'] ?? true);

        $isApiException = $object->getClass() === ApiException::class;

        $data = [
            'status' => $context['status'] ?? $object->getStatusCode(),
            'detail' => $debug || $isApiException ? $object->getMessage() : $object->getStatusText(),
        ];

        if ($debug) {
            $data['class'] = $object->getClass();
            $data['trace'] = $object->getTrace();
        }

        return $data;
    }

    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return $data instanceof FlattenException;
    }
}

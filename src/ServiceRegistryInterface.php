<?php

declare(strict_types=1);

namespace Highcore\Component\Registry;

use Highcore\Component\Registry\Exception\ExistingServiceException;
use Highcore\Component\Registry\Exception\NonExistingServiceException;

/**
 * @template T
 */
interface ServiceRegistryInterface
{
    /**
     * @return array<string, T>
     */
    public function all(): array;

    /**
     * @param T $service
     *
     * @throws ExistingServiceException
     * @throws \InvalidArgumentException
     */
    public function register(object $service): void;

    /**
     * @throws NonExistingServiceException
     */
    public function unregister(string|object  $service): void;

    public function has(string|object $service): bool;
}

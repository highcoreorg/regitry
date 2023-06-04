<?php

declare(strict_types=1);

namespace Highcore\Component\Registry;

use Highcore\Component\Registry\Exception\ExistingServiceException;
use Highcore\Component\Registry\Exception\NonExistingServiceException;

/**
 * @template T
 *
 * @template-implements IdentityServiceRegistryInterface<T>
 */
final class IdentityServiceRegistry implements IdentityServiceRegistryInterface
{
    /** @var array<string, T> */
    private array $services = [];

    /**
     * @param null|class-string<T> $interface
     */
    public function __construct(
        private readonly ?string $interface = null,
        private readonly string $context = 'service'
    ) {
    }

    public function all(): array
    {
        return $this->services;
    }

    /**
     * @param T $service
     */
    public function register(string $identifier, $service): void
    {
        if ($this->has($identifier)) {
            throw ExistingServiceException::createFromContextAndType($this->context, $identifier);
        }

        if (null !== $this->interface && !$service instanceof $this->interface) {
            throw new \InvalidArgumentException(
                sprintf('%s needs to be of type "%s", "%s" given.', ucfirst($this->context), $this->interface, get_class($service))
            );
        }

        $this->services[$identifier] = $service;
    }

    public function unregister(string $identifier): void
    {
        if (!$this->has($identifier)) {
            throw NonExistingServiceException::createFromContextAndType($this->context, $identifier, array_keys($this->services));
        }

        unset($this->services[$identifier]);
    }

    public function has(string $identifier): bool
    {
        return isset($this->services[$identifier]);
    }

    /**
     * @return T
     */
    public function get(string $identifier): object
    {
        if (!$this->has($identifier)) {
            throw NonExistingServiceException::createFromContextAndType($this->context, $identifier, array_keys($this->services));
        }

        return $this->services[$identifier];
    }
}
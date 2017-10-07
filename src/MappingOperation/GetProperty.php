<?php

namespace AutoMapperPlus\MappingOperation;

use AutoMapperPlus\Configuration\AutoMapperConfigInterface;
use AutoMapperPlus\NameResolver\NameResolverInterface;
use AutoMapperPlus\PrivateAccessor\PrivateAccessor;

/**
 * Class GetProperty
 *
 * An operation that simply extracts the value of a property of the source
 * object. A custom name resolver can be provided.
 *
 * @package AutoMapperPlus\MappingOperation
 */
class GetProperty implements MappingOperationInterface
{
    /**
     * @var NameResolverInterface
     */
    private $nameResolver;

    /**
     * GetProperty constructor.
     *
     * @param NameResolverInterface $nameResolver
     */
    public function __construct(NameResolverInterface $nameResolver)
    {
        $this->nameResolver = $nameResolver;
    }

    /**
     * @inheritdoc
     */
    public function __invoke
    (
        $from,
        $to,
        string $propertyName,
        AutoMapperConfigInterface $config
    ): void
    {
        $fromReflectionClass = new \ReflectionClass($from);
        $sourcePropertyName = $this->nameResolver->resolve($propertyName);
        $sourceProperty = $fromReflectionClass->getProperty($sourcePropertyName);
        if ($sourceProperty->isPublic()) {
            $to->{$propertyName} = $from->{$sourcePropertyName};
        }
        else {
            $to->{$propertyName} = PrivateAccessor::getPrivate($from, $sourcePropertyName);
        }
    }

}
<?php

namespace Lemo\Filter;

use Laminas\Filter\AbstractFilter;
use Traversable;

use function hash;

class Hash extends AbstractFilter
{
    /** @var array{algorithm: string|null} */
    protected $options = [
        'algorithm' => null,
    ];

    public function __construct(Traversable|array|null $options = null)
    {
        if ($options !== null) {
            $this->setOptions($options);
        }
    }

    /**
     * @param  mixed $value
     * @return mixed
     */
    public function filter($value): mixed
    {
        if (!is_scalar($value)) {
            return $value;
        }

        $value = (string) $value;

        $value = hash($this->getAlgorithm(), $value);

        return false === $value ? null : $value;
    }

    public function setAlgorithm(string $algorithm): self
    {
        $this->options['algorithm'] = $algorithm;

        return $this;
    }

    public function getAlgorithm(): string
    {
        if (empty($this->options['algorithm'])) {
            throw new Exception\InvalidArgumentException(
                sprintf(

                    '%s expects a "algorithm" option; none given',
                    self::class
                )
            );
        }

        return $this->options['algorithm'];
    }
}
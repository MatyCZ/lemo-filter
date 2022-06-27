<?php

namespace Lemo\Filter;

use Laminas\Filter\AbstractFilter;
use Traversable;

use function hash;
use function sprintf;

class Hash extends AbstractFilter
{
    /** @var array{algorithm: string|null} */
    protected $options = [
        'algorithm' => null,
    ];


    /**
     * @param Traversable<string,string>|array{algorithm: string|null}|null $options
     */
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

        return hash($this->getAlgorithm(), $value);
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
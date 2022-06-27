<?php

namespace Lemo\Filter;

use Laminas\Filter\AbstractFilter;
use Traversable;

use function is_scalar;
use function sprintf;
use function str_pad;

class StrPad extends AbstractFilter
{
    /** @var array{pad_length: int|null, pad_string: string|null, pad_type: int|null} */
    protected $options = [
        'pad_length' => null,
        'pad_string' => null,
        'pad_type'   => null,
    ];

    /**
     * @param Traversable<string,int|string|null>|array{pad_length: int|null, pad_string: string|null, pad_type: int|null}|null $options
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

        return str_pad($value, $this->getPadLength(), $this->getPadString(), $this->getPadType());
    }

    public function setPadLength(?int $padLength): self
    {
        $this->options['pad_length'] = $padLength;

        return $this;
    }

    public function getPadLength(): int
    {
        if (empty($this->options['pad_length'])) {
            throw new Exception\InvalidArgumentException(
                sprintf(

                    '%s expects a "pad_length" option; none given',
                    self::class
                )
            );
        }

        return $this->options['pad_length'];
    }

    public function setPadString(?string $padString): self
    {
        $this->options['pad_string'] = $padString;

        return $this;
    }

    public function getPadString(): string
    {
        if (empty($this->options['pad_string'])) {
            throw new Exception\InvalidArgumentException(
                sprintf(
                    '%s expects a "pad_string" option; none given',
                    self::class
                )
            );
        }

        return $this->options['pad_string'];
    }

    public function setPadType(?int $padType): self
    {
        $this->options['pad_type'] = $padType;

        return $this;
    }

    public function getPadType(): int
    {
        if (empty($this->options['pad_type'])) {
            throw new Exception\InvalidArgumentException(
                sprintf(
                    '%s expects a "pad_type" option; none given',
                    self::class
                )
            );
        }

        return $this->options['pad_type'];
    }
}

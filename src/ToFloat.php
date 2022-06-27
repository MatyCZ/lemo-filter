<?php

namespace Lemo\Filter;

use Laminas\Filter\AbstractFilter;
use Traversable;

use function floatval;
use function is_scalar;
use function number_format;
use function preg_match;
use function preg_replace;
use function round;
use function sprintf;

class ToFloat extends AbstractFilter
{
    /** @var array{precision: int} */
    protected $options = [
        'precision' => 4,
    ];

    /**
     * @param Traversable<string,int>|array{precision: int}|null $options
     */
    public function __construct(Traversable|array|null $options = null)
    {
        if ($options !== null) {
            $this->setOptions($options);
        }
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    public function filter($value): mixed
    {
        if (!is_scalar($value)) {
            return $value;
        }

        $value = (string) $value;

        $isNegative = (bool) preg_match('~^\-~', $value);
        $value = preg_replace('~[^0-9\.]~', '', $value);

        $value = floatval($value);
        $value = round($value, $this->getPrecision());
        $value = number_format($value, $this->getPrecision(), '.', '');

        if (true === $isNegative) {
            $value = '-' . $value;
        }

        return $value;
    }

    public function setPrecision(int $precision): self
    {
        $this->options['precision'] = $precision;

        return $this;
    }

    public function getPrecision(): int
    {
        if (empty($this->options['precision'])) {
            throw new Exception\InvalidArgumentException(
                sprintf(
                    '%s expects a "precision" option; none given',
                    self::class
                )
            );
        }

        return $this->options['precision'];
    }
}
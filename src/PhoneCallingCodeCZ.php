<?php

namespace Lemo\Filter;

use Laminas\Filter\AbstractFilter;
use Traversable;

use function is_scalar;
use function preg_match;
use function sprintf;

class PhoneCallingCodeCZ extends AbstractFilter
{
    /** @var array{callingCode: string} */
    protected $options = [
        'callingCode' => '420',
    ];

    /**
     * @param Traversable<string,string>|array{callingCode: string}|null $options
     */
    public function __construct(Traversable|array|null $options = null)
    {
        if ($options !== null) {
            $this->setOptions($options);
        }
    }

    /**
     * Returns $value in international phone number format
     *
     * @param mixed $value
     * @return mixed
     */
    public function filter($value): mixed
    {
        if (!is_scalar($value) || '' === $value) {
            return $value;
        }

        $value = (string) $value;

        if (!$this->getCallingCode()) {
            return $value;
        }

        if (preg_match('~^(\++)?(' . $this->getCallingCode() . ')?([0-9]+){1}$~', $value, $m)) {
            // Is not a number
            if (empty($m[3])) {
                return $value;
            }

            // Probably another prefix
            if (!empty($m[1]) && empty($m[2])) {
                return $value;
            }

            return '+' . $this->getCallingCode() . $m[3];
        }

        return $value;
    }

    public function setCallingCode(string $callingCode): self
    {
        $this->options['callingCode'] = $callingCode;

        return $this;
    }

    public function getCallingCode(): string
    {
        if (empty($this->options['callingCode'])) {
            throw new Exception\InvalidArgumentException(
                sprintf(
                    '%s expects a "callingCode" option; none given',
                    self::class
                )
            );
        }

        return $this->options['callingCode'];
    }
}

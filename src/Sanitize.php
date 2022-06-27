<?php

namespace Lemo\Filter;

use Laminas\Filter\AbstractFilter;
use Laminas\Filter\StringToLower;
use Laminas\Filter\StringTrim;
use Traversable;

use function function_exists;
use function is_scalar;
use function preg_replace;
use function sprintf;
use function trim;

class Sanitize extends AbstractFilter
{
    /** @var array{encoding: string|null, lowercase: bool|null, separator: string|null} */
    protected $options = [
        'encoding' => null,
        'lowercase' => true,
        'separator' => '-',
    ];

    /**
     * @param Traversable<string,bool|string|null>|array{encoding: string|null, lowercase: bool|null, separator: string|null}|null $options
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
        if (!is_scalar($value) || '' === $value) {
            return $value;
        }

        $value = (string) $value;

        $value = $this->trimString($value);
        $value = $this->convertToAscii($value);
        $value = $this->convertToLowerCase($value);
        $value = $this->convertToLetterAndDigits($value);

        return $value;
    }

    /**
     * Trim spaces, tabs and newlines
     */
    protected function trimString(string $value): string
    {
        $filter = new StringTrim();

        return $filter->filter($value);
    }

    /**
     * Convert value to ASCII
     */
    protected function convertToAscii(string $value): string
    {
        $filter = new Transliteration();

        return $filter->filter($value);
    }

    /**
     * Convert value to lowercase
     */
    protected function convertToLowerCase(string $value): string
    {
        if (true !== $this->getLowercase()) {
            return $value;
        }

        $filter = new StringToLower();
        $filter->setEncoding($this->getEncoding());

        return $filter->filter($value);
    }

    /**
     * Convert value to string with letters and digits
     */
    protected function convertToLetterAndDigits(string $value): string
    {
        $valueModified = preg_replace('~[^\\pL\d]+~u', $this->getSeparator(), $value);

        if (null !== $valueModified) {
            return trim($valueModified, $this->getSeparator());
        }

        return $value;
    }

    public function setEncoding(?string $encoding): self
    {
        $this->options['encoding'] = $encoding;

        return $this;
    }

    public function getEncoding(): string
    {
        if (empty($this->options['encoding'])) {
            if (function_exists('mb_internal_encoding')) {
                $this->options['encoding'] = mb_internal_encoding();
            }

            if (empty($this->options['encoding'])) {
                throw new Exception\InvalidArgumentException(
                    sprintf(
                        '%s expects a "encoding" option; none given',
                        self::class
                    )
                );
            }
        }

        return $this->options['encoding'];
    }

    public function setLowercase(bool $lowercase): self
    {
        $this->options['lowercase'] = $lowercase;

        return $this;
    }

    public function getLowercase(): bool
    {
        if (empty($this->options['lowercase'])) {
            throw new Exception\InvalidArgumentException(
                sprintf(
                    '%s expects a "lowercase" option; none given',
                    self::class
                )
            );
        }

        return $this->options['lowercase'];
    }

    public function setSeparator(?string $separator): self
    {
        $this->options['separator'] = $separator;

        return $this;
    }

    public function getSeparator(): string
    {
        if (empty($this->options['separator'])) {
            throw new Exception\InvalidArgumentException(
                sprintf(
                    '%s expects a "separator" option; none given',
                    self::class
                )
            );
        }

        return $this->options['separator'];
    }
}

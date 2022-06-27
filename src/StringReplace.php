<?php

namespace Lemo\Filter;

use Laminas\Filter\AbstractFilter;
use Laminas\Filter\Exception;
use Traversable;

use function is_scalar;
use function sprintf;
use function str_replace;

class StringReplace extends AbstractFilter
{
    /** @var array{replace: array<string>|string|null, search: array<string>|string|null} */
    protected $options = [
        'replace' => '',
        'search' => null,
    ];

    /**
     * @param Traversable<string, array<string>|string|null>|array{replace: array<string>|string|null, search: array<string>|string|null}|null $options
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

        return str_replace(
            $this->getSearch(),
            $this->getReplace(),
            $value
        );
    }

    /**
     * Set the value being searched for
     *
     * @see str_replace()
     *
     * @param array<string>|string $search
     * @throws Exception\InvalidArgumentException
     */
    public function setSearch(array|string $search): self
    {
        $this->options['search'] = $search;

        return $this;
    }

    /**
     * Get currently set value being searched for
     *
     * @return array<string>|string
     */
    public function getSearch(): array|string
    {
        if (empty($this->options['search'])) {
            throw new Exception\InvalidArgumentException(
                sprintf(
                    '%s expects a "search" option; none given',
                    self::class
                )
            );
        }

        return $this->options['search'];
    }

    /**
     * Set the replacement array/string
     *
     * @see str_replace()
     *
     * @param array<string>|string $replace
     * @throws Exception\InvalidArgumentException
     */
    public function setReplace(array|string $replace): self
    {
        $this->options['replace'] = $replace;

        return $this;
    }

    /**
     * Get currently set replace value
     *
     * @return array<string>|string
     */
    public function getReplace(): array|string
    {
        if (empty($this->options['replace'])) {
            throw new Exception\InvalidArgumentException(
                sprintf(
                    '%s expects a "search" option; none given',
                    self::class
                )
            );
        }

        return $this->options['replace'];
    }
}

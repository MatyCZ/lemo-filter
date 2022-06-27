<?php

namespace Lemo\Filter;

use DateTime;
use Exception;
use Laminas\Filter\AbstractFilter;

use function is_scalar;
use function preg_match;

class DateTimeObject extends AbstractFilter
{
    /**
     * Returns $value as DateTimeObject
     *
     * @param  mixed $value
     * @return mixed
     * @throws Exception
     */
    public function filter($value): mixed
    {
        if ($value instanceof DateTime) {
            return $value;
        }

        if (!is_scalar($value) || '' === $value) {
            return $value;
        }

        $value = (string) $value;

        if (preg_match('~^([0-9]{1,2}\.) ([0-9]{1,2}\.) ([0-9]{2,4})(.*)$~', $value, $m)) {
            $value = $m[1] . $m[2] . $m[3] . $m[4];
        }

        return new DateTime($value);
    }
}
<?php

namespace Lemo\Filter;

use Laminas\Filter\AbstractFilter;
use Laminas\Filter\StringTrim;

use function is_scalar;
use function ltrim;
use function preg_match;
use function str_replace;

class BankAccount extends AbstractFilter
{
    /**
     * Returns $value as CZ bank account number
     *
     * @param  mixed $value
     * @return mixed
     */
    public function filter($value): mixed
    {
        if (!is_scalar($value)) {
            return $value;
        }

        $value = (string) $value;
        $value = $this->stringTrim($value);
        $value = $this->stripSpaces($value);

        $accountPrefix = '';
        $accountNo = '';
        $bankCode = '';

        if (preg_match('~^\-?([0-9]{2,10})\/([0-9]{4})$~', $value, $m)) {
            $accountNo = $this->stripLeadingZeros($m[1]);
            $bankCode = $m[2];
        } elseif (
            preg_match('~^([0-9]{2,6})\-([0-9]{2,10})\/([0-9]{4})$~', $value, $m)
            || preg_match('~^([0-9]{1,6})([0-9]{10})\/([0-9]{4})$~', $value, $m)
        ) {
            $accountPrefix = $this->stripLeadingZeros($m[1]);
            $accountNo = $this->stripLeadingZeros($m[2]);
            $bankCode = $m[3];
        }

        if (empty($bankCode) || empty($accountNo)) {
            $value = '';
        } else {
            $value = $accountNo . '/' . $bankCode;
            if (!empty($accountPrefix)) {
                $value = $accountPrefix . '-' . $value;
            }
        }

        return $value;
    }

    /**
     * Trim spaces, tabs and newlines
     */
    protected function stringTrim(string $value): string
    {
        return (new StringTrim())->filter($value);
    }

    /**
     * Strip spaces
     */
    protected function stripSpaces(string $value): string
    {
        return str_replace(' ', '', $value);
    }

    /**
     * Strip leading zeros
     */
    protected function stripLeadingZeros(string $value): string
    {
        return ltrim($value, '0');
    }
}

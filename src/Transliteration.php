<?php

namespace Lemo\Filter;

use Laminas\Filter\AbstractFilter;
use Locale;
use Transliterator;

use function is_scalar;
use function setlocale;

use const LC_ALL;

class Transliteration extends AbstractFilter
{
    /**
     * @param  mixed $value
     * @return mixed
     */
    public function filter($value): mixed
    {
        if (!is_scalar($value)) {
            return $value;
        }

        if (setlocale(LC_ALL, '0') == 'C') {
            setlocale(LC_ALL, Locale::getDefault());
        }

        $transliterator = Transliterator::createFromRules(
            ':: Any-Latin; :: Latin-ASCII;',
            Transliterator::FORWARD
        );

        if (null === $transliterator) {
            return $value;
        }

        $value = (string) $value;

        return $transliterator->transliterate($value);
    }
}

<?php

namespace Lemo\Filter;

use Laminas\Filter\AbstractFilter;

use function html_entity_decode;
use function is_scalar;

class HtmlEntitiesDecode extends AbstractFilter
{
    public function filter($value): mixed
    {
        if (!is_scalar($value)) {
            return $value;
        }

        $value = (string) $value;

        return html_entity_decode($value);
    }
}

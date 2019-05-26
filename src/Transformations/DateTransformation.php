<?php

namespace Cerbero\Transformer\Transformations;

use DateTime;
use Illuminate\Support\Carbon;

/**
 * Transform a value into a date time instance or a formatted date.
 *
 */
class DateTransformation extends AbstractTransformation
{
    /**
     * Apply the transformation
     *
     * @param array $parameters
     * @return mixed
     */
    public function apply(array $parameters)
    {
        if (!isset($parameters[0])) {
            return new DateTime($this->value);
        }

        $format = $parameters[0];
        $fromTimezone = $parameters[1] ?? 'UTC';
        $toTimezone = $parameters[2] ?? 'UTC';

        return Carbon::parse($this->value, $fromTimezone)
            ->timezone($toTimezone)
            ->format($format);
    }
}

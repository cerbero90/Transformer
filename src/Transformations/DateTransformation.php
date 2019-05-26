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
            return $this->getDate();
        }

        $format = $parameters[0];
        $fromTimezone = $parameters[1] ?? 'UTC';
        $toTimezone = $parameters[2] ?? 'UTC';

        return Carbon::parse($this->value, $fromTimezone)
            ->timezone($toTimezone)
            ->format($format);
    }

    /**
     * Retrieve the date time instance
     *
     * @return DateTime
     */
    protected function getDate(): DateTime
    {
        if ($this->value instanceof DateTime) {
            return $this->value;
        }

        return new DateTime($this->value);
    }
}

<?php

/**
 *
 * @author Stefano Torresi <webdeveloper@stefanotorresi.it>
 */

namespace MyBase\View\Helper;

use IntlDateFormatter;
use Zend\I18n\View\Helper\DateFormat;

class DatePatternFormat extends DateFormat
{
    /**
     * @var string
     */
    protected $pattern;

    /**
     * pattern can be passed as second argument
     *
     * {@inheritdoc}
     */
    public function __invoke(
        $date = null,
        $dateType = IntlDateFormatter::NONE,
        $timeType = IntlDateFormatter::NONE,
        $locale = null,
        $pattern = null
    )
    {
        if ($date === null) {
            return $this;
        }

        if (is_string($dateType)) {
            $realPattern = $dateType;
            $dateType = $timeType;
            $timeType = $locale;
            $locale = $pattern;
            $pattern = $realPattern;
        }

        if (! $pattern) {
            $pattern = $this->getPattern();
        }

        return parent::__invoke($date, $dateType, $timeType, $locale, $pattern);
    }

    /**
     * @return mixed
     */
    public function getPattern()
    {
        return $this->pattern;
    }

    /**
     * @param  mixed $pattern
     * @return $this
     */
    public function setPattern($pattern)
    {
        $this->pattern = $pattern;

        return $this;
    }
}

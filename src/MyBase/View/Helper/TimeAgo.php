<?php

/**
 *
 * @author Stefano Torresi <webdeveloper@stefanotorresi.it>
 */

namespace MyBase\View\Helper;

use Zend\I18n\View\Helper\AbstractTranslatorHelper;

class TimeAgo extends AbstractTranslatorHelper
{
    /**
     * 
     * @param string $timeString
     * @return string
     */
    public function __invoke($timeString = null)
    {
        
        if ($timeString === null ) {
            return $this;
        }
        
        $time = strtotime($timeString);
        
        if ($time === false) {
            throw new \InvalidArgumentException(sprintf(
                '%s needs a strtotime() compatible string',
                get_class($this)
            ));
        }
        
        $timeAgo = time() - $time;

        $divisors = array (
            31536000 => 'year',
            2592000 => 'month',
            604800 => 'week',
            86400 => 'day',
            3600 => 'hour',
            60 => 'minute',
            1 => 'second'
        );

        foreach ($divisors as $divisor => $text) {
            if ($timeAgo < $divisor) {
                continue;
            }
            
            $number = floor($timeAgo / $divisor);
            $result = $number .' '
                .$this->translator->translatePlural($text.' ago', $text.'s'.' ago', $number);
             
            return $result;
        }
    }
}

<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyBase\Test\View\Helper;

use MyBase\View\Helper\DatePatternFormat;
use ZendTest\I18n\View\Helper\DateFormatTest;

class DatePatternFormatTest extends DateFormatTest
{
    /**
     * @var DatePatternFormat
     */
    public $helper;

    public function setUp()
    {
        if (! extension_loaded('intl')) {
            $this->markTestSkipped('ext/intl not enabled');
        }

        $this->helper = new DatePatternFormat();
    }

    public function testInvokeWithoutArgumentsReturnSelf()
    {
        $this->assertSame($this->helper, $this->helper->__invoke());
    }

    /**
     * @dataProvider dateTestsDataProviderWithPattern
     */
    public function testPatternSetterProvidesDefault($locale, $timezone, $timeType, $dateType, $pattern, $date)
    {
        $this->helper
            ->setPattern($pattern)
            ->setTimezone($timezone)
        ;

        $expected = $this->getIntlDateFormatter($locale, $dateType, $timeType, $timezone, $pattern)
            ->format($date->getTimestamp());

        $this->assertMbStringEquals($expected, $this->helper->__invoke(
            $date, $dateType, $timeType, $locale
        ));
    }

    /**
     * @dataProvider dateTestsDataProviderWithPattern
     */
    public function testPatternAsSecondArgument($locale, $timezone, $timeType, $dateType, $pattern, $date)
    {
        $this->helper->setTimezone($timezone);

        $expected = $this->getIntlDateFormatter($locale, $dateType, $timeType, $timezone, $pattern)
                         ->format($date->getTimestamp());

        $this->assertMbStringEquals($expected, $this->helper->__invoke($date, $pattern, $dateType, $timeType, $locale));
    }
}

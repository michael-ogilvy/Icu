<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Icu\Tests\ResourceBundle;

use Symfony\Component\Icu\ResourceBundle\RegionBundle;

/**
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class RegionBundleTest extends \PHPUnit_Framework_TestCase
{
    const RES_DIR = '/base/region';

    /**
     * @var RegionBundle
     */
    private $bundle;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $reader;

    protected function setUp()
    {
        $this->reader = $this->getMock('Symfony\Component\Icu\ResourceBundle\Reader\ResourceEntryReaderInterface');
        $this->bundle = new RegionBundle(self::RES_DIR, $this->reader);
    }

    public function testGetCountryName()
    {
        $this->reader->expects($this->once())
            ->method('readEntry')
            ->with(self::RES_DIR, 'en', array('Countries', 'AT'))
            ->will($this->returnValue('Austria'));

        $this->assertSame('Austria', $this->bundle->getCountryName('en', 'AT'));
    }

    public function testGetCountryNames()
    {
        $sortedCountries = array(
            'AT' => 'Austria',
            'DE' => 'Germany',
        );

        $this->reader->expects($this->once())
            ->method('readMergedEntry')
            ->with(self::RES_DIR, 'en', array('Countries'))
            ->will($this->returnValue($sortedCountries));


        $this->assertSame($sortedCountries, $this->bundle->getCountryNames('en'));
    }
}

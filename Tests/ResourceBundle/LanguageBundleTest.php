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

use Symfony\Component\Icu\ResourceBundle\LanguageBundle;

/**
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class LanguageBundleTest extends \PHPUnit_Framework_TestCase
{
    const RES_DIR = '/base/lang';

    /**
     * @var LanguageBundle
     */
    private $bundle;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $reader;

    protected function setUp()
    {
        $this->reader = $this->getMock('Symfony\Component\Icu\ResourceBundle\Reader\ResourceEntryReaderInterface');
        $this->bundle = new LanguageBundle(self::RES_DIR, $this->reader);
    }

    public function testGetLanguageName()
    {
        $languages = array(
            'de' => 'German',
            'en' => 'English',
        );

        $this->reader->expects($this->once())
            ->method('readEntry')
            ->with(self::RES_DIR, 'en', array('Languages'))
            ->will($this->returnValue($languages));

        $this->assertSame('German', $this->bundle->getLanguageName('en', 'de'));
    }

    public function testGetLanguageNameWithRegion()
    {
        $languages = array(
            'de' => 'German',
            'en' => 'English',
            'en_GB' => 'British English',
        );

        $this->reader->expects($this->once())
            ->method('readEntry')
            ->with(self::RES_DIR, 'en', array('Languages'))
            ->will($this->returnValue($languages));

        $this->assertSame('British English', $this->bundle->getLanguageName('en', 'en', 'GB'));
    }

    public function testGetLanguageNameWithUntranslatedRegion()
    {
        $languages = array(
            'de' => 'German',
            'en' => 'English',
        );

        $this->reader->expects($this->once())
            ->method('readEntry')
            ->with(self::RES_DIR, 'en', array('Languages'))
            ->will($this->returnValue($languages));

        $this->assertSame('English', $this->bundle->getLanguageName('en', 'en', 'US'));
    }

    public function testGetLanguageNames()
    {
        $sortedLanguages = array(
            'en' => 'English',
            'de' => 'German',
        );

        $this->reader->expects($this->once())
            ->method('readEntry')
            ->with(self::RES_DIR, 'en', array('Languages'))
            ->will($this->returnValue($sortedLanguages));

        $this->assertSame($sortedLanguages, $this->bundle->getLanguageNames('en'));
    }

    public function testGetScriptName()
    {
        $data = array(
            'Languages' => array(
                'de' => 'German',
                'en' => 'English',
            ),
            'Scripts' => array(
                'Latn' => 'latin',
                'Cyrl' => 'cyrillique',
            ),
        );

        $this->reader->expects($this->once())
            ->method('readEntry')
            ->with(self::RES_DIR, 'en', array())
            ->will($this->returnValue($data));

        $this->assertSame('latin', $this->bundle->getScriptName('en', 'Latn'));
    }

    public function testGetScriptNameIncludedInLanguage()
    {
        $data = array(
            'Languages' => array(
                'de' => 'German',
                'en' => 'English',
                'zh_Hans' => 'Simplified Chinese',
            ),
            'Scripts' => array(
                'Latn' => 'latin',
                'Cyrl' => 'cyrillique',
            ),
        );

        $this->reader->expects($this->once())
            ->method('readEntry')
            ->with(self::RES_DIR, 'en', array())
            ->will($this->returnValue($data));

        // Null because the script is included in the language anyway
        $this->assertNull($this->bundle->getScriptName('en', 'Hans', 'zh'));
    }

    public function testGetScriptNameIncludedInLanguageInBraces()
    {
        $data = array(
            'Languages' => array(
                'de' => 'German',
                'en' => 'English',
                'zh_Hans' => 'Chinese (simplified)',
            ),
            'Scripts' => array(
                'Latn' => 'latin',
                'Cyrl' => 'cyrillique',
            ),
        );

        $this->reader->expects($this->once())
            ->method('readEntry')
            ->with(self::RES_DIR, 'en', array())
            ->will($this->returnValue($data));

        $this->assertSame('simplified', $this->bundle->getScriptName('en', 'Hans', 'zh'));
    }

    public function testGetScriptNameNoScriptsBlock()
    {
        $data = array(
            'Languages' => array(
                'de' => 'German',
                'en' => 'English',
            ),
        );

        $this->reader->expects($this->once())
            ->method('readEntry')
            ->with(self::RES_DIR, 'en', array())
            ->will($this->returnValue($data));

        $this->assertNull($this->bundle->getScriptName('en', 'Latn'));
    }

    public function testGetScriptNames()
    {
        $sortedScripts = array(
            'Cyrl' => 'cyrillique',
            'Latn' => 'latin',
        );

        $this->reader->expects($this->once())
            ->method('readMergedEntry')
            ->with(self::RES_DIR, 'en', array('Scripts'))
            ->will($this->returnValue($sortedScripts));

        $this->assertSame($sortedScripts, $this->bundle->getScriptNames('en'));
    }
}

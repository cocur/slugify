<?php

/**
 * This file is part of cocur/slugify.
 *
 * (c) Florian Eckerstorfer <florian@eckerstorfer.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cocur\Slugify\Tests;

use Cocur\Slugify\Slugify;

/**
 * SlugifyTest
 *
 * @category  test
 * @package   org.cocur.slugify
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @author    Ivo Bathke <ivo.bathke@gmail.com>
 * @author    Marchenko Alexandr
 * @copyright 2012-2014 Florian Eckerstorfer
 * @license   http://www.opensource.org/licenses/MIT The MIT License
 */
class SlugifyTest extends \PHPUnit_Framework_TestCase
{
    private $slugify;

    public function setUp()
    {
        $this->slugify = new Slugify();
    }

    /**
     * @test
     * @dataProvider provider
     * @covers Cocur\Slugify\Slugify::slugify()
     */
    public function slugifyReturnsSlugifiedString($string, $result)
    {
        $this->assertEquals($result, $this->slugify->slugify($string));
    }

    /**
     * @test
     * @covers Cocur\Slugify\Slugify::addRule()
     * @covers Cocur\Slugify\Slugify::slugify()
     */
    public function addRuleAddsRule()
    {
        $this->slugify->addRule('X', 'y');
        $this->assertEquals('y', $this->slugify->slugify('X'));
    }

    /**
     * @test
     * @covers Cocur\Slugify\Slugify::addRules()
     * @covers Cocur\Slugify\Slugify::slugify()
     */
    public function addRulesAddsMultipleRules()
    {
        $this->slugify->addRules(array('x' => 'y', 'a' => 'b'));
        $this->assertEquals('yb', $this->slugify->slugify('xa'));
    }

    /**
     * @test
     * @covers Cocur\Slugify\Slugify::activateRuleset()
     */
    public function activateRulesetActivatesTheGivenRuleset()
    {
        $this->slugify->activateRuleset('esperanto');

        $this->assertEquals(
            'sercxi-mangxi-hxirurgio-jxurnalo-sxuo-malgraux',
            $this->slugify->slugify('serĉi manĝi ĥirurgio ĵurnalo ŝuo malgraŭ')
        );
    }

    /**
     * @test
     * @covers Cocur\Slugify\Slugify::activateRuleset()
     * @expectedException \InvalidArgumentException
     */
    public function activateRulesetThrowsExceptionIfInvalidName()
    {
        $this->slugify->activateRuleset('invalid');
    }

    /**
     * @test
     * @covers  Cocur\Slugify\Slugify::addRuleset()
     * @covers  Cocur\Slugify\Slugify::getRulesets()
     */
    public function addRulesetGetRulesets()
    {
        $this->slugify->addRuleset('foo', array('k' => 'key'));

        $this->assertCount(2, $this->slugify->getRulesets());
    }

    /**
     * @test
     * @covers Cocur\Slugify\Slugify::create()
     */
    public function createReturnsAnInstance()
    {
        $this->assertInstanceOf('Cocur\\Slugify\\SlugifyInterface', Slugify::create());
    }

    /**
     * @test
     * @covers Cocur\Slugify\Slugify::setRegExp()
     */
    public function otherRegExpsProduceOtherResults()
    {
        $actual = 'File Name.tar.gz';
        $expected = 'file-name.tar.gz';

        $this->assertNotEquals($expected, $this->slugify->slugify($actual));
        $this->slugify->setRegExp('/([^a-z0-9.]|-)+/');
        $this->assertEquals($expected, $this->slugify->slugify($actual));
    }

    /**
     * @test
     * @covers Cocur\Slugify\Slugify::__construct()
     */
    public function constructWithOtherRegexp()
    {
        $actual = 'File Name.tar.gz';
        $expected = 'file-name.tar.gz';

        $this->slugify = new Slugify('/([^a-z0-9.]|-)+/');
        $this->assertEquals($expected, $this->slugify->slugify($actual));
    }

    public function provider()
    {
        return array(
            array(' a  b ', 'a-b'),
            array('Hello', 'hello'),
            array('Hello World', 'hello-world'),
            array('Привет мир', 'privet-mir'),
            array('Привіт світ', 'privit-svit'),
            array('Hello: World', 'hello-world'),
            array('H+e#l1l--o/W§o r.l:d)', 'h-e-l1l-o-w-o-r-l-d'),
            array(': World', 'world'),
            array('Hello World!', 'hello-world'),
            array('Ä ä Ö ö Ü ü ß', 'ae-ae-oe-oe-ue-ue-ss'),
            array('Á À á à É È é è Ó Ò ó ò Ñ ñ Ú Ù ú ù', 'a-a-a-a-e-e-e-e-o-o-o-o-n-n-u-u-u-u'),
            array('Â â Ê ê Ô ô Û û', 'a-a-e-e-o-o-u-u'),
            array('Â â Ê ê Ô ô Û 1', 'a-a-e-e-o-o-u-1'),
            array('°¹²³@', '0123at'),
            array('Mórë thån wørds', 'more-than-words'),
            array('Блоґ їжачка', 'blog-jizhachka'),
            array('фильм', 'film'),
            array('драма', 'drama'),
            array('ελληνικά', 'ellenika'),
            array('C’est du français !', 'c-est-du-francais'),
            array('هذه هي اللغة العربية', 'hthh-hy-llgh-laarby'),
            array('مرحبا العالم', 'mrhb-laa-lm'),
            array('Één jaar', 'een-jaar'),
            array('tiếng việt rất khó', 'tieng-viet-rat-kho'),
            array('Nguyễn Đăng Khoa', 'nguyen-dang-khoa'),
            array('နှစ်သစ်ကူးတွင် သတ္တဝါတွေ စိတ်ချမ်းသာ ကိုယ်ကျန်းမာ၍ ကောင်းခြင်း အနန္တနှင့် ပြည့်စုံကြပါစေ', 'nhitthitkutwin-thttwatwe-seikkhyaantha-koekyaanmaywae-kaungkhyin-anntnhin-pyisonkypase'),
            array('Zażółć żółcią gęślą jaźń', 'zazolc-zolcia-gesla-jazn'),
            array('Mężny bądź chroń pułk twój i sześć flag', 'mezny-badz-chron-pulk-twoj-i-szesc-flag')
        );
    }
}

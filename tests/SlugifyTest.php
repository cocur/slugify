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
use Mockery;

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
    /**
     * @var Slugify
     */
    private $slugify;

    /**
     * @var \Cocur\Slugify\RuleProvider\RuleProviderInterface|\Mockery\MockInterface
     */
    private $provider;

    protected function setUp()
    {
        $this->provider = Mockery::mock('\Cocur\Slugify\RuleProvider\RuleProviderInterface');
        $this->provider->shouldReceive('getRules')->andReturn([]);

        $this->slugify = new Slugify([], $this->provider);
    }

    /**
     * @test
     * @dataProvider defaultRuleProvider
     * @covers       Cocur\Slugify\Slugify::slugify()
     */
    public function slugifyReturnsSlugifiedStringUsingDefaultProvider($string, $result)
    {
        $slugify = new Slugify();

        $this->assertEquals($result, $slugify->slugify($string));
    }

    /**
     * @test
     * @covers Cocur\Slugify\Slugify::addRule()
     * @covers Cocur\Slugify\Slugify::slugify()
     */
    public function addRuleAddsRule()
    {
        $this->assertInstanceOf(
            'Cocur\Slugify\Slugify',
            $this->slugify->addRule('X', 'y')
        );
        $this->assertEquals('y', $this->slugify->slugify('X'));
    }

    /**
     * @test
     * @covers Cocur\Slugify\Slugify::addRules()
     * @covers Cocur\Slugify\Slugify::slugify()
     */
    public function addRulesAddsMultipleRules()
    {
        $this->assertInstanceOf(
            'Cocur\Slugify\Slugify',
            $this->slugify->addRules(['x' => 'y', 'a' => 'b'])
        );
        $this->assertEquals('yb', $this->slugify->slugify('xa'));
    }

    /**
     * @test
     * @covers Cocur\Slugify\Slugify::activateRuleset()
     */
    public function activateRulesetActivatesTheGivenRuleset()
    {
        $provider = Mockery::mock('\Cocur\Slugify\RuleProvider\RuleProviderInterface');
        $provider->shouldReceive('getRules')->with('esperanto')->once()->andReturn(['ĉ' => 'cx']);

        $slugify = new Slugify(['rulesets' => []], $provider);
        $this->assertInstanceOf(
            'Cocur\Slugify\Slugify',
            $slugify->activateRuleset('esperanto')
        );

        $this->assertEquals('sercxi', $slugify->slugify('serĉi'));
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
     * @covers Cocur\Slugify\Slugify::__construct()
     */
    public function constructWithOtherRegexp()
    {
        $this->slugify = new Slugify(['regexp' => '/([^a-z0-9.]|-)+/']);

        $this->assertEquals('file-name.tar.gz', $this->slugify->slugify('File Name.tar.gz'));
    }

    /**
     * @test
     * @covers Cocur\Slugify\Slugify::__construct()
     * @covers Cocur\Slugify\Slugify::slugify()
     */
    public function doNotConvertToLowercase()
    {
        $actual   = 'File Name';
        $expected = 'File-Name';

        $this->slugify = new Slugify(['lowercase' => false]);
        $this->assertEquals($expected, $this->slugify->slugify($actual));
    }

    /**
     * @test
     * @dataProvider customRulesProvider
     */
    public function customRules($rule, $string, $result)
    {
        $slugify = new Slugify();
        $slugify->activateRuleSet($rule);

        $this->assertSame($result, $slugify->slugify($string));
    }

    public function customRulesProvider()
    {
        return [
            ['azerbaijani', 'əöüğşçı', 'eougsci'],
            ['azerbaijani', 'Fərhad Səfərov', 'ferhad-seferov'],
            ['croatian', 'Č Ć Ž Š Đ č ć ž š đ', 'c-c-z-s-dj-c-c-z-s-dj'],
            ['danish', 'Æ æ Ø ø Å å É é', 'ae-ae-oe-oe-aa-aa-e-e'],
        ];
    }

    /**
     * @test
     * @covers Cocur\Slugify\Slugify::__construct()
     * @covers Cocur\Slugify\Slugify::slugify()
     */
    public function slugifyDefaultsToSeparatorOption()
    {
        $actual   = 'file name';
        $expected = 'file__name';

        $this->slugify = new Slugify(['separator' => '__']);
        $this->assertEquals($expected, $this->slugify->slugify($actual));
    }

    /**
     * @test
     * @covers Cocur\Slugify\Slugify::__construct()
     * @covers Cocur\Slugify\Slugify::slugify()
     */
    public function slugifyHonorsSeparatorArgument()
    {
        $actual   = 'file name';
        $expected = 'file__name';

        $this->slugify = new Slugify(['separator' => 'dummy']);
        $this->assertEquals($expected, $this->slugify->slugify($actual, '__'));
    }

    /**
     * @test
     * @covers Cocur\Slugify\Slugify::slugify()
     */
    public function slugifyOptionsArray()
    {
        $this->assertEquals('file-name', $this->slugify->slugify('file name'));
        $this->assertEquals('file+name', $this->slugify->slugify('file name', ['separator' => '+']));

        $this->assertEquals('name-1', $this->slugify->slugify('name(1)'));
        $this->assertEquals('name(1)', $this->slugify->slugify('name(1)', ['regexp' => '/([^a-z0-9.()]|-)+/']));

        $this->assertEquals('file-name', $this->slugify->slugify('FILE NAME'));
        $this->assertEquals('FILE-NAME', $this->slugify->slugify('FILE NAME', ['lowercase' => false]));
    }

    /**
     * @test
     * @covers Cocur\Slugify\Slugify::slugify()
     */
    public function slugifyCustomRuleSet()
    {
        $slugify = new Slugify();

        $this->assertSame('fur', $slugify->slugify('für', ['ruleset' => 'turkish']));
        $this->assertSame('fuer', $slugify->slugify('für'));
    }

    public function defaultRuleProvider()
    {
        return [
            [' a  b ', 'a-b'],
            ['Hello', 'hello'],
            ['Hello World', 'hello-world'],
            ['Привет мир', 'privet-mir'],
            ['Привіт світ', 'privit-svit'],
            ['Hello: World', 'hello-world'],
            ['H+e#l1l--o/W§o r.l:d)', 'h-e-l1l-o-w-o-r-l-d'],
            [': World', 'world'],
            ['Hello World!', 'hello-world'],
            ['Ä ä Ö ö Ü ü ß', 'ae-ae-oe-oe-ue-ue-ss'],
            ['Á À á à É È é è Ó Ò ó ò Ñ ñ Ú Ù ú ù', 'a-a-a-a-e-e-e-e-o-o-o-o-n-n-u-u-u-u'],
            ['Â â Ê ê Ô ô Û û', 'a-a-e-e-o-o-u-u'],
            ['Â â Ê ê Ô ô Û 1', 'a-a-e-e-o-o-u-1'],
            ['°¹²³⁴⁵⁶⁷⁸⁹@₀₁₂₃₄₅₆₇₈₉', '0123456789at0123456789'],
            ['Mórë thån wørds', 'more-thaan-woerds'],
            ['Блоґ їжачка', 'blog-jizhachka'],
            ['фильм', 'film'],
            ['драма', 'drama'],
            ['Ύπαρξη Αυτής η Σκουληκομυρμηγκότρυπα', 'iparxi-autis-i-skoulikomirmigkotripa'],
            ['C’est du français !', 'c-est-du-francais'],
            ['هذه هي اللغة العربية', 'hthh-hy-llgh-laarby'],
            ['مرحبا العالم', 'mrhb-laa-lm'],
            ['Één jaar', 'een-jaar'],
            ['tiếng việt rất khó', 'tieng-viet-rat-kho'],
            ['Nguyễn Đăng Khoa', 'nguyen-dang-khoa'],
            ['နှစ်သစ်ကူးတွင် သတ္တဝါတွေ စိတ်ချမ်းသာ ကိုယ်ကျန်းမာ၍ ကောင်းခြင်း အနန္တနှင့် ပြည့်စုံကြပါစေ', 'nhitthitkutwin-thttwatwe-seikkhyaantha-koekyaanmaywae-kaungkhyin-anntnhin-pyisonkypase'],
            ['Zażółć żółcią gęślą jaźń', 'zazolc-zolcia-gesla-jazn'],
            ['Mężny bądź chroń pułk twój i sześć flag', 'mezny-badz-chron-pulk-twoj-i-szesc-flag'],
            ['ერთი ორი სამი ოთხი ხუთი', 'erti-ori-sami-otkhi-khuti'],
            ['अ ऒ न द', 'a-oii-na-tha'],
            ['Æ Ø Å æ ø å', 'ae-oe-aa-ae-oe-aa'],
            [str_repeat('Übergrößenträger', 1000), str_repeat('uebergroessentraeger', 1000)],
            [str_repeat('my🎉', 5000), substr(str_repeat('my-', 5000), 0, -1)],
            [str_repeat('hi🇦🇹', 5000), substr(str_repeat('hi-', 5000), 0, -1)],
			['Č Ć Ž Š Đ č ć ž š đ', 'c-c-z-s-d-c-c-z-s-d'],
        ];
    }
}

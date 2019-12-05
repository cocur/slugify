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
use Mockery\Adapter\Phpunit\MockeryTestCase;

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
class SlugifyTest extends MockeryTestCase
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
     * @dataProvider defaultRuleProvider
     * @covers       \Cocur\Slugify\Slugify::slugify()
     */
    public function testSlugifyReturnsSlugifiedStringUsingDefaultProvider($string, $result)
    {
        $slugify = new Slugify();

        $this->assertEquals($result, $slugify->slugify($string));
    }

    /**
     * @covers \Cocur\Slugify\Slugify::addRule()
     * @covers \Cocur\Slugify\Slugify::slugify()
     */
    public function testAddRuleAddsRule()
    {
        $this->assertInstanceOf(
            'Cocur\Slugify\Slugify',
            $this->slugify->addRule('X', 'y')
        );
        $this->assertEquals('y', $this->slugify->slugify('X'));
    }

    /**
     * @covers \Cocur\Slugify\Slugify::addRules()
     * @covers \Cocur\Slugify\Slugify::slugify()
     */
    public function testAddRulesAddsMultipleRules()
    {
        $this->assertInstanceOf(
            'Cocur\Slugify\Slugify',
            $this->slugify->addRules(['x' => 'y', 'a' => 'b'])
        );
        $this->assertEquals('yb', $this->slugify->slugify('xa'));
    }

    /**
     * @covers \Cocur\Slugify\Slugify::activateRuleset()
     */
    public function testActivateRulesetActivatesTheGivenRuleset()
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
     * @covers \Cocur\Slugify\Slugify::create()
     */
    public function testCreateReturnsAnInstance()
    {
        $this->assertInstanceOf('Cocur\\Slugify\\SlugifyInterface', Slugify::create());
    }

    /**
     * @covers \Cocur\Slugify\Slugify::__construct()
     */
    public function testConstructWithOtherRegexp()
    {
        $this->slugify = new Slugify(['regexp' => '/([^a-z0-9.]|-)+/']);

        $this->assertEquals('file-name.tar.gz', $this->slugify->slugify('File Name.tar.gz'));
    }

    /**
     * @covers \Cocur\Slugify\Slugify::__construct()
     * @covers \Cocur\Slugify\Slugify::slugify()
     */
    public function testDoNotConvertToLowercase()
    {
        $actual   = 'File Name';
        $expected = 'File-Name';

        $this->slugify = new Slugify(['lowercase' => false]);
        $this->assertEquals($expected, $this->slugify->slugify($actual));
    }

    /**
     * @dataProvider customRulesProvider
     */
    public function testCustomRules($rule, $string, $result)
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
            ['romanian', 'ă î â ş ș ţ ț Ă Î Â Ş Ș Ţ Ț', 'a-i-a-s-s-t-t-a-i-a-s-s-t-t'],
            ['serbian', 'А Б В Г Д Ђ Е Ж З И Ј К Л Љ М Н Њ О П Р С Т Ћ У Ф Х Ц Ч Џ Ш а б в г д ђ е ж з и ј к л љ м н њ о п р с т ћ у ф х ц ч џ ш Š Đ Ž Ć Č š đ ž ć č', 'a-b-v-g-d-dj-e-z-z-i-j-k-l-lj-m-n-nj-o-p-r-s-t-c-u-f-h-c-c-dz-s-a-b-v-g-d-dj-e-z-z-i-j-k-l-lj-m-n-nj-o-p-r-s-t-c-u-f-h-c-c-dz-s-s-dj-z-c-c-s-dj-z-c-c'],
            ['slovak', 'Á Ä Č Ď É Í Ĺ Ľ Ň Ó Ô Ŕ Š Ť Ú Ý Ž á ä č ď é í ĺ ľ ň ó ô ŕ š ť ú ý ž', 'a-a-c-d-e-i-l-l-n-o-o-r-s-t-u-y-z-a-a-c-d-e-i-l-l-n-o-o-r-s-t-u-y-z'],
            ['lithuanian', 'Ą Č Ę Ė Į Š Ų Ū Ž ą č ę ė į š ų ū ž', 'a-c-e-e-i-s-u-u-z-a-c-e-e-i-s-u-u-z'],
            ['estonian', 'Š Ž Õ Ä Ö Ü š ž õ ä ö ü', 's-z-o-a-o-u-s-z-o-a-o-u'],
            ['hungarian', 'Á É Í Ó Ö Ő Ú Ü Ű á é í ó ö ő ú ü ű', 'a-e-i-o-o-o-u-u-u-a-e-i-o-o-o-u-u-u'],
            ['macedonian', 'Ѓезвето беше полно со црно кафе. Ѕ ѕ s', 'gjezveto-beshe-polno-so-crno-kafe-dz-dz-s'],
            ['chinese', '活动日起', 'huodongriqi'],
            ['turkmen', 'Ç Ä Ž Ň Ö Ş Ü Ý ç ä ž ň ö ş ü ý', 'c-a-z-n-o-s-u-y-c-a-z-n-o-s-u-y'],
        ];
    }

    /**
     * @covers \Cocur\Slugify\Slugify::__construct()
     * @covers \Cocur\Slugify\Slugify::slugify()
     */
    public function testSlugifyDefaultsToSeparatorOption()
    {
        $actual   = 'file name';
        $expected = 'file__name';

        $this->slugify = new Slugify(['separator' => '__']);
        $this->assertEquals($expected, $this->slugify->slugify($actual));
    }

    /**
     * @covers \Cocur\Slugify\Slugify::__construct()
     * @covers \Cocur\Slugify\Slugify::slugify()
     */
    public function testSlugifyHonorsSeparatorArgument()
    {
        $actual   = 'file name';
        $expected = 'file__name';

        $this->slugify = new Slugify(['separator' => 'dummy']);
        $this->assertEquals($expected, $this->slugify->slugify($actual, '__'));
    }

    /**
     * @covers \Cocur\Slugify\Slugify::slugify()
     */
    public function testSlugifyOptionsArray()
    {
        $this->assertEquals('file-name', $this->slugify->slugify('file name'));
        $this->assertEquals('file+name', $this->slugify->slugify('file name', ['separator' => '+']));

        $this->assertEquals('name-1', $this->slugify->slugify('name(1)'));
        $this->assertEquals('name(1)', $this->slugify->slugify('name(1)', ['regexp' => '/([^a-z0-9.()]|-)+/']));

        $this->assertEquals('file-name', $this->slugify->slugify('FILE NAME'));
        $this->assertEquals('FILE-NAME', $this->slugify->slugify('FILE NAME', ['lowercase' => false]));

        $this->assertEquals('file-name', $this->slugify->slugify('file name '));
        $this->assertEquals('file-name-', $this->slugify->slugify('file name ', ['trim' => false]));

        $this->assertEquals('file-name', $this->slugify->slugify('<file name'));
        $this->assertEquals('p-file-p-foo-a-href-bar-name-a', $this->slugify->slugify('<p>file</p><!-- foo --> <a href="#bar">name</a>'));
        $this->assertEquals('file-name', $this->slugify->slugify('<p>file</p><!-- foo --> <a href="#bar">name</a>', ['strip_tags' => true]));
    }

    /**
     * @covers \Cocur\Slugify\Slugify::slugify()
     */
    public function testSlugifyCustomRuleSet()
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
            ['Ä ä Ö ö Ü ü ẞ ß', 'ae-ae-oe-oe-ue-ue-ss-ss'],
            ['Á À á à É È é è Ó Ò ó ò Ñ ñ Ú Ù ú ù', 'a-a-a-a-e-e-e-e-o-o-o-o-n-n-u-u-u-u'],
            ['Â â Ê ê Ô ô Û û', 'a-a-e-e-o-o-u-u'],
            ['Â â Ê ê Ô ô Û 1', 'a-a-e-e-o-o-u-1'],
            ['°¹²³⁴⁵⁶⁷⁸⁹@₀₁₂₃₄₅₆₇₈₉', '0123456789at0123456789'],
            ['Mórë thån wørds', 'more-thaan-woerds'],
            ['Блоґ їжачка', 'blog-jizhachka'],
            ['фильм', 'film'],
            ['драма', 'drama'],
            ['Ύπαρξη Αυτής η Σκουληκομυρμηγκότρυπα', 'iparxi-autis-i-skoulikomirmigkotripa'],
            ['Français Œuf où à', 'francais-oeuf-ou-a'],
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
            ['Ą Č Ę Ė Į Š Ų Ū Ž ą č ę ė į š ų ū ž', 'a-c-e-e-i-s-u-u-z-a-c-e-e-i-s-u-u-z'],
        ];
    }

    /**
     * @covers \Cocur\Slugify\Slugify::slugify()
     */
    public function testSlugifyLowercaseNotAfterRegexp()
    {
        $slugify = new Slugify();

        // Matches any non-uppercase letter followed by an uppercase letter,
        // which means it  must be used before lowercasing the result.
        $regexp = '/(?<=[[:^upper:]])(?=[[:upper:]])/';

        $this->assertSame('foobar', $slugify->slugify('FooBar', [
            'regexp' => $regexp,
            'lowercase' => true,
            'lowercase_after_regexp' => false,
            'separator' => '_',
        ]));
    }

    /**
     * @covers \Cocur\Slugify\Slugify::slugify()
     */
    public function testSlugifyLowercaseAfterRegexp()
    {
        $slugify = new Slugify();

        // Matches any non-uppercase letter followed by an uppercase letter,
        // which means it  must be used before lowercasing the result.
        $regexp = '/(?<=[[:^upper:]])(?=[[:upper:]])/';

        $this->assertSame('foo_bar', $slugify->slugify('FooBar', [
            'regexp' => $regexp,
            'lowercase' => true,
            'lowercase_after_regexp' => true,
            'separator' => '_',
        ]));
    }
}

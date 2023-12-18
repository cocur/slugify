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

    protected function setUp(): void
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
        $this->assertSame('y', $this->slugify->slugify('X'));
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
        $this->assertSame('yb', $this->slugify->slugify('xa'));
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

        $this->assertSame('sercxi', $slugify->slugify('serĉi'));
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

        $this->assertSame('file-name.tar.gz', $this->slugify->slugify('File Name.tar.gz'));
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
        $this->assertSame($expected, $this->slugify->slugify($actual));
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
            ['custom-fonts', '-͕a͕ ͕b͕ ͕c͕ ͕d͕ ͕e͕ ͕f͕ ͕g͕ ͕h͕ ͕i͕ ͕j͕ ͕k͕ ͕l͕ ͕m͕ ͕n͕ ͕o͕ ͕p͕ ͕q͕ ͕r͕ ͕s͕ ͕t͕ ͕u͕ ͕v͕ ͕w͕ ͕x͕ ͕y͕ ͕z͕ A͕ ͕B͕ ͕C͕ ͕D͕ ͕E͕ ͕F͕ ͕G͕ ͕H͕ ͕I͕ ͕J͕ ͕K͕ ͕L͕ ͕M͕ ͕N͕ ͕O͕ ͕P͕ ͕Q͕ ͕R͕ ͕S͕ ͕T͕ ͕U͕ ͕V͕ ͕W͕ ͕X͕ ͕Y͕ ͕Z͕-͜͡a͜͡ ͜͡b͜͡ ͜͡c͜͡ ͜͡d͜͡ ͜͡e͜͡ ͜͡f͜͡ ͜͡g͜͡ ͜͡h͜͡ ͜͡i͜͡ ͜͡j͜͡ ͜͡k͜͡ ͜͡l͜͡ ͜͡m͜͡ ͜͡n͜͡ ͜͡o͜͡ ͜͡p͜͡ ͜͡q͜͡ ͜͡r͜͡ ͜͡s͜͡ ͜͡t͜͡ ͜͡u͜͡ ͜͡v͜͡ ͜͡w͜͡ ͜͡x͜͡ ͜͡y͜͡ ͜͡z͜͡ A͜͡ ͜͡B͜͡ ͜͡C͜͡ ͜͡D͜͡ ͜͡E͜͡ ͜͡F͜͡ ͜͡G͜͡ ͜͡H͜͡ ͜͡I͜͡ ͜͡J͜͡ ͜͡K͜͡ ͜͡L͜͡ ͜͡M͜͡ ͜͡N͜͡ ͜͡O͜͡ ͜͡P͜͡ ͜͡Q͜͡ ͜͡R͜͡ ͜͡S͜͡ ͜͡T͜͡ ͜͡U͜͡ ͜͡V͜͡ ͜͡W͜͡ ͜͡X͜͡ ͜͡Y͜͡ ͜͡Z͜͡-𝐚 𝐛 𝐜 𝐝 𝐞 𝐟 𝐠 𝐡 𝐢 𝐣 𝐤 𝐥 𝐦 𝐧 𝐨 𝐩 𝐪 𝐫 𝐬 𝐭 𝐮 𝐯 𝐰 𝐱 𝐲 𝐳 𝐀 𝐁 𝐂 𝐃 𝐄 𝐅 𝐆 𝐇 𝐈 𝐉 𝐊 𝐋 𝐌 𝐍 𝐎 𝐏 𝐐 𝐑 𝐒 𝐓 𝐔 𝐕 𝐖 𝐗 𝐘 𝐙-𝑎 𝑏 𝑐 𝑑 𝑒 𝑓 𝑔 ℎ 𝑖 𝑗 𝑘 𝑙 𝑚 𝑛 𝑜 𝑝 𝑞 𝑟 𝑠 𝑡 𝑢 𝑣 𝑤 𝑥 𝑦 𝑧 𝐴 𝐵 𝐶 𝐷 𝐸 𝐹 𝐺 𝐻 𝐼 𝐽 𝐾 𝐿 𝑀 𝑁 𝑂 𝑃 𝑄 𝑅 𝑆 𝑇 𝑈 𝑉 𝑊 𝑋 𝑌 𝑍-͛⦚a͛⦚ ͛⦚b͛⦚ ͛⦚c͛⦚ ͛⦚d͛⦚ ͛⦚e͛⦚ ͛⦚f͛⦚ ͛⦚g͛⦚ ͛⦚h͛⦚ ͛⦚i͛⦚ ͛⦚j͛⦚ ͛⦚k͛⦚ ͛⦚l͛⦚ ͛⦚m͛⦚ ͛⦚n͛⦚ ͛⦚o͛⦚ ͛⦚p͛⦚ ͛⦚q͛⦚ ͛⦚r͛⦚ ͛⦚s͛⦚ ͛⦚t͛⦚ ͛⦚u͛⦚ ͛⦚v͛⦚ ͛⦚w͛⦚ ͛⦚x͛⦚ ͛⦚y͛⦚ ͛⦚z͛⦚ A͛⦚ ͛⦚B͛⦚ ͛⦚C͛⦚ ͛⦚D͛⦚ ͛⦚E͛⦚ ͛⦚F͛⦚ ͛⦚G͛⦚ ͛⦚H͛⦚ ͛⦚I͛⦚ ͛⦚J͛⦚ ͛⦚K͛⦚ ͛⦚L͛⦚ ͛⦚M͛⦚ ͛⦚N͛⦚ ͛⦚O͛⦚ ͛⦚P͛⦚ ͛⦚Q͛⦚ ͛⦚R͛⦚ ͛⦚S͛⦚ ͛⦚T͛⦚ ͛⦚U͛⦚ ͛⦚V͛⦚ ͛⦚W͛⦚ ͛⦚X͛⦚ ͛⦚Y͛⦚ ͛⦚Z͛⦚-̺a̺ ̺b̺ ̺c̺ ̺d̺ ̺e̺ ̺f̺ ̺g̺ ̺h̺ ̺i̺ ̺j̺ ̺k̺ ̺l̺ ̺m̺ ̺n̺ ̺o̺ ̺p̺ ̺q̺ ̺r̺ ̺s̺ ̺t̺ ̺u̺ ̺v̺ ̺w̺ ̺x̺ ̺y̺ ̺z̺ A̺ ̺B̺ ̺C̺ ̺D̺ ̺E̺ ̺F̺ ̺G̺ ̺H̺ ̺I̺ ̺J̺ ̺K̺ ̺L̺ ̺M̺ ̺N̺ ̺O̺ ̺P̺ ̺Q̺ ̺R̺ ̺S̺ ̺T̺ ̺U̺ ̺V̺ ̺W̺ ̺X̺ ̺Y̺ ̺Z̺-̳̳̼̟̮ͨ́ͫ͜͠͠͞͞a̳̳̼̟̮ͨ́ͫ͜͠͠͞͞ ̳̳̼̟̮ͨ́ͫ͜͠͠͞͞b̳̳̼̟̮ͨ́ͫ͜͠͠͞͞ ̳̳̼̟̮ͨ́ͫ͜͠͠͞͞c̳̳̼̟̮ͨ́ͫ͜͠͠͞͞ ̳̳̼̟̮ͨ́ͫ͜͠͠͞͞d̳̳̼̟̮ͨ́ͫ͜͠͠͞͞ ̳̳̼̟̮ͨ́ͫ͜͠͠͞͞e̳̳̼̟̮ͨ́ͫ͜͠͠͞͞ ̳̳̼̟̮ͨ́ͫ͜͠͠͞͞f̳̳̼̟̮ͨ́ͫ͜͠͠͞͞ ̳̳̼̟̮ͨ́ͫ͜͠͠͞͞g̳̳̼̟̮ͨ́ͫ͜͠͠͞͞ ̳̳̼̟̮ͨ́ͫ͜͠͠͞͞h̳̳̼̟̮ͨ́ͫ͜͠͠͞͞ ̳̳̼̟̮ͨ́ͫ͜͠͠͞͞i̳̳̼̟̮ͨ́ͫ͜͠͠͞͞ ̳̳̼̟̮ͨ́ͫ͜͠͠͞͞j̳̳̼̟̮ͨ́ͫ͜͠͠͞͞ ̳̳̼̟̮ͨ́ͫ͜͠͠͞͞k̳̳̼̟̮ͨ́ͫ͜͠͠͞͞ ̳̳̼̟̮ͨ́ͫ͜͠͠͞͞l̳̳̼̟̮ͨ́ͫ͜͠͠͞͞ ̳̳̼̟̮ͨ́ͫ͜͠͠͞͞m̳̳̼̟̮ͨ́ͫ͜͠͠͞͞ ̳̳̼̟̮ͨ́ͫ͜͠͠͞͞n̳̳̼̟̮ͨ́ͫ͜͠͠͞͞ ̳̳̼̟̮ͨ́ͫ͜͠͠͞͞o̳̳̼̟̮ͨ́ͫ͜͠͠͞͞ ̳̳̼̟̮ͨ́ͫ͜͠͠͞͞p̳̳̼̟̮ͨ́ͫ͜͠͠͞͞ ̳̳̼̟̮ͨ́ͫ͜͠͠͞͞q̳̳̼̟̮ͨ́ͫ͜͠͠͞͞ ̳̳̼̟̮ͨ́ͫ͜͠͠͞͞r̳̳̼̟̮ͨ́ͫ͜͠͠͞͞ ̳̳̼̟̮ͨ́ͫ͜͠͠͞͞s̳̳̼̟̮ͨ́ͫ͜͠͠͞͞ ̳̳̼̟̮ͨ́ͫ͜͠͠͞͞t̳̳̼̟̮ͨ́ͫ͜͠͠͞͞ ̳̳̼̟̮ͨ́ͫ͜͠͠͞͞u̳̳̼̟̮ͨ́ͫ͜͠͠͞͞ ̳̳̼̟̮ͨ́ͫ͜͠͠͞͞v̳̳̼̟̮ͨ́ͫ͜͠͠͞͞ ̳̳̼̟̮ͨ́ͫ͜͠͠͞͞w̳̳̼̟̮ͨ́ͫ͜͠͠͞͞ ̳̳̼̟̮ͨ́ͫ͜͠͠͞͞x̳̳̼̟̮ͨ́ͫ͜͠͠͞͞ ̳̳̼̟̮ͨ́ͫ͜͠͠͞͞y̳̳̼̟̮ͨ́ͫ͜͠͠͞͞ ̳̳̼̟̮ͨ́ͫ͜͠͠͞͞z̳̳̼̟̮ͨ́ͫ͜͠͠͞͞ A̳̳̼̟̮ͨ́ͫ͜͠͠͞͞ ̳̳̼̟̮ͨ́ͫ͜͠͠͞͞B̳̳̼̟̮ͨ́ͫ͜͠͠͞͞ ̳̳̼̟̮ͨ́ͫ͜͠͠͞͞C̳̳̼̟̮ͨ́ͫ͜͠͠͞͞ ̳̳̼̟̮ͨ́ͫ͜͠͠͞͞D̳̳̼̟̮ͨ́ͫ͜͠͠͞͞ ̳̳̼̟̮ͨ́ͫ͜͠͠͞͞E̳̳̼̟̮ͨ́ͫ͜͠͠͞͞ ̳̳̼̟̮ͨ́ͫ͜͠͠͞͞F̳̳̼̟̮ͨ́ͫ͜͠͠͞͞ ̳̳̼̟̮ͨ́ͫ͜͠͠͞͞G̳̳̼̟̮ͨ́ͫ͜͠͠͞͞ ̳̳̼̟̮ͨ́ͫ͜͠͠͞͞H̳̳̼̟̮ͨ́ͫ͜͠͠͞͞ ̳̳̼̟̮ͨ́ͫ͜͠͠͞͞I̳̳̼̟̮ͨ́ͫ͜͠͠͞͞ ̳̳̼̟̮ͨ́ͫ͜͠͠͞͞J̳̳̼̟̮ͨ́ͫ͜͠͠͞͞ ̳̳̼̟̮ͨ́ͫ͜͠͠͞͞K̳̳̼̟̮ͨ́ͫ͜͠͠͞͞ ̳̳̼̟̮ͨ́ͫ͜͠͠͞͞L̳̳̼̟̮ͨ́ͫ͜͠͠͞͞ ̳̳̼̟̮ͨ́ͫ͜͠͠͞͞M̳̳̼̟̮ͨ́ͫ͜͠͠͞͞ ̳̳̼̟̮ͨ́ͫ͜͠͠͞͞N̳̳̼̟̮ͨ́ͫ͜͠͠͞͞ ̳̳̼̟̮ͨ́ͫ͜͠͠͞͞O̳̳̼̟̮ͨ́ͫ͜͠͠͞͞ ̳̳̼̟̮ͨ́ͫ͜͠͠͞͞P̳̳̼̟̮ͨ́ͫ͜͠͠͞͞ ̳̳̼̟̮ͨ́ͫ͜͠͠͞͞Q̳̳̼̟̮ͨ́ͫ͜͠͠͞͞ ̳̳̼̟̮ͨ́ͫ͜͠͠͞͞R̳̳̼̟̮ͨ́ͫ͜͠͠͞͞ ̳̳̼̟̮ͨ́ͫ͜͠͠͞͞S̳̳̼̟̮ͨ́ͫ͜͠͠͞͞ ̳̳̼̟̮ͨ́ͫ͜͠͠͞͞T̳̳̼̟̮ͨ́ͫ͜͠͠͞͞ ̳̳̼̟̮ͨ́ͫ͜͠͠͞͞U̳̳̼̟̮ͨ́ͫ͜͠͠͞͞ ̳̳̼̟̮ͨ́ͫ͜͠͠͞͞V̳̳̼̟̮ͨ́ͫ͜͠͠͞͞ ̳̳̼̟̮ͨ́ͫ͜͠͠͞͞W̳̳̼̟̮ͨ́ͫ͜͠͠͞͞ ̳̳̼̟̮ͨ́ͫ͜͠͠͞͞X̳̳̼̟̮ͨ́ͫ͜͠͠͞͞ ̳̳̼̟̮ͨ́ͫ͜͠͠͞͞Y̳̳̼̟̮ͨ́ͫ͜͠͠͞͞ ̳̳̼̟̮ͨ́ͫ͜͠͠͞͞Z̳̳̼̟̮ͨ́ͫ͜͠͠͞͞-𝒶 𝒷 𝒸 𝒹 𝑒 𝒻 𝑔 𝒽 𝒾 𝒿 𝓀 𝓁 𝓂 𝓃 𝑜 𝓅 𝓆 𝓇 𝓈 𝓉 𝓊 𝓋 𝓌 𝓍 𝓎 𝓏 𝒜 𝐵 𝒞 𝒟 𝐸 𝐹 𝒢 𝐻 𝐼 𝒥 𝒦 𝐿 𝑀 𝒩 𝒪 𝒫 𝒬 𝑅 𝒮 𝒯 𝒰 𝒱 𝒲 𝒳 𝒴 𝒵-͎a͎ ͎b͎ ͎c͎ ͎d͎ ͎e͎ ͎f͎ ͎g͎ ͎h͎ ͎i͎ ͎j͎ ͎k͎ ͎l͎ ͎m͎ ͎n͎ ͎o͎ ͎p͎ ͎q͎ ͎r͎ ͎s͎ ͎t͎ ͎u͎ ͎v͎ ͎w͎ ͎x͎ ͎y͎ ͎z͎ A͎ ͎B͎ ͎C͎ ͎D͎ ͎E͎ ͎F͎ ͎G͎ ͎H͎ ͎I͎ ͎J͎ ͎K͎ ͎L͎ ͎M͎ ͎N͎ ͎O͎ ͎P͎ ͎Q͎ ͎R͎ ͎S͎ ͎T͎ ͎U͎ ͎V͎ ͎W͎ ͎X͎ ͎Y͎ ͎Z͎-ɐ q ɔ p ǝ ɟ ƃ ɥ ᴉ ɾ ʞ l ɯ u o d b ɹ s ʇ n ʌ ʍ x ʎ z ɐ q ɔ p ǝ ɟ ƃ ɥ ᴉ ɾ ʞ l ɯ u o d b ɹ s ʇ n ʌ ʍ x ʎ z-᷈a᷈ ᷈b᷈ ᷈c᷈ ᷈d᷈ ᷈e᷈ ᷈f᷈ ᷈g᷈ ᷈h᷈ ᷈i᷈ ᷈j᷈ ᷈k᷈ ᷈l᷈ ᷈m᷈ ᷈n᷈ ᷈o᷈ ᷈p᷈ ᷈q᷈ ᷈r᷈ ᷈s᷈ ᷈t᷈ ᷈u᷈ ᷈v᷈ ᷈w᷈ ᷈x᷈ ᷈y᷈ ᷈z᷈ A᷈ ᷈B᷈ ᷈C᷈ ᷈D᷈ ᷈E᷈ ᷈F᷈ ᷈G᷈ ᷈H᷈ ᷈I᷈ ᷈J᷈ ᷈K᷈ ᷈L᷈ ᷈M᷈ ᷈N᷈ ᷈O᷈ ᷈P᷈ ᷈Q᷈ ᷈R᷈ ᷈S᷈ ᷈T᷈ ᷈U᷈ ᷈V᷈ ᷈W᷈ ᷈X᷈ ᷈Y᷈ ᷈Z᷈-ᄉaᄉ ᄉbᄉ ᄉcᄉ ᄉdᄉ ᄉeᄉ ᄉfᄉ ᄉgᄉ ᄉhᄉ ᄉiᄉ ᄉjᄉ ᄉkᄉ ᄉlᄉ ᄉmᄉ ᄉnᄉ ᄉoᄉ ᄉpᄉ ᄉqᄉ ᄉrᄉ ᄉsᄉ ᄉtᄉ ᄉuᄉ ᄉvᄉ ᄉwᄉ ᄉxᄉ ᄉyᄉ ᄉzᄉ ᄉAᄉ ᄉBᄉ ᄉCᄉ ᄉDᄉ ᄉEᄉ ᄉFᄉ ᄉGᄉ ᄉHᄉ ᄉIᄉ ᄉJᄉ ᄉKᄉ ᄉLᄉ ᄉMᄉ ᄉNᄉ ᄉOᄉ ᄉPᄉ ᄉQᄉ ᄉRᄉ ᄉSᄉ ᄉTᄉ ᄉUᄉ ᄉVᄉ ᄉWᄉ ᄉXᄉ ᄉYᄉ ᄉZᄉ-҈a҈ ҈b҈ ҈c҈ ҈d҈ ҈e҈ ҈f҈ ҈g҈ ҈h҈ ҈i҈ ҈j҈ ҈k҈ ҈l҈ ҈m҈ ҈n҈ ҈o҈ ҈p҈ ҈q҈ ҈r҈ ҈s҈ ҈t҈ ҈u҈ ҈v҈ ҈w҈ ҈x҈ ҈y҈ ҈z҈ ҈A҈ ҈B҈ ҈C҈ ҈D҈ ҈E҈ ҈F҈ ҈G҈ ҈H҈ ҈I҈ ҈J҈ ҈K҈ ҈L҈ ҈M҈ ҈N҈ ҈O҈ ҈P҈ ҈Q҈ ҈R҈ ҈S҈ ҈T҈ ҈U҈ ҈V҈ ҈W҈ ҈X҈ ҈Y҈ ҈Z҈-͙a͙ ͙b͙ ͙c͙ ͙d͙ ͙e͙ ͙f͙ ͙g͙ ͙h͙ ͙i͙ ͙j͙ ͙k͙ ͙l͙ ͙m͙ ͙n͙ ͙o͙ ͙p͙ ͙q͙ ͙r͙ ͙s͙ ͙t͙ ͙u͙ ͙v͙ ͙w͙ ͙x͙ ͙y͙ ͙z͙ A͙ ͙B͙ ͙C͙ ͙D͙ ͙E͙ ͙F͙ ͙G͙ ͙H͙ ͙I͙ ͙J͙ ͙K͙ ͙L͙ ͙M͙ ͙N͙ ͙O͙ ͙P͙ ͙Q͙ ͙R͙ ͙S͙ ͙T͙ ͙U͙ ͙V͙ ͙W͙ ͙X͙ ͙Y͙ ͙Z͙-̺͆a̺͆ ̺͆b̺͆ ̺͆c̺͆ ̺͆d̺͆ ̺͆e̺͆ ̺͆f̺͆ ̺͆g̺͆ ̺͆h̺͆ ̺͆i̺͆ ̺͆j̺͆ ̺͆k̺͆ ̺͆l̺͆ ̺͆m̺͆ ̺͆n̺͆ ̺͆o̺͆ ̺͆p̺͆ ̺͆q̺͆ ̺͆r̺͆ ̺͆s̺͆ ̺͆t̺͆ ̺͆u̺͆ ̺͆v̺͆ ̺͆w̺͆ ̺͆x̺͆ ̺͆y̺͆ ̺͆z̺͆ A̺͆ ̺͆B̺͆ ̺͆C̺͆ ̺͆D̺͆ ̺͆E̺͆ ̺͆F̺͆ ̺͆G̺͆ ̺͆H̺͆ ̺͆I̺͆ ̺͆J̺͆ ̺͆K̺͆ ̺͆L̺͆ ̺͆M̺͆ ̺͆N̺͆ ̺͆O̺͆ ̺͆P̺͆ ̺͆Q̺͆ ̺͆R̺͆ ̺͆S̺͆ ̺͆T̺͆ ̺͆U̺͆ ̺͆V̺͆ ̺͆W̺͆ ̺͆X̺͆ ̺͆Y̺͆ ̺͆Z̺͆-ะ𝗮ะ ะ𝗯ะ ะ𝗰ะ ะ𝗱ะ ะ𝗲ะ ะ𝗳ะ ะ𝗴ะ ะ𝗵ะ ะ𝗶ะ ะ𝗷ะ ะ𝗸ะ ะ𝗹ะ ะ𝗺ะ ะ𝗻ะ ะ𝗼ะ ะ𝗽ะ ะ𝗾ะ ะ𝗿ะ ะ𝘀ะ ะ𝘁ะ ะ𝘂ะ v ะ𝘄ะ ะ𝘅ะ ะ𝘆ะ ะ𝘇ะ ะ𝗔ะ ะ𝗕ะ ะ𝗖ะ ะ𝗗ะ ะ𝗘ะ ะ𝗙ะ ะ𝗚ะ ะ𝗛ะ ะ𝗜ะ ะ𝗝ะ ะ𝗞ะ ะ𝗟ะ ะ𝗠ะ ะ𝗡ะ ะ𝗢ะ ะ𝗣ะ ะ𝗤ะ ะ𝗥ะ ะ𝗦ะ ะ𝗧ะ ะ𝗨ะ V ะ𝗪ะ ะ𝗫ะ ะ𝗬ะ ะ𝗭ะ-』『a』『 』『b』『 』『c』『 』『d』『 』『e』『 』『f』『 』『g』『 』『h』『 』『i』『 』『j』『 』『k』『 』『l』『 』『m』『 』『n』『 』『o』『 』『p』『 』『q』『 』『r』『 』『s』『 』『t』『 』『u』『 』『v』『 』『w』『 』『x』『 』『y』『 』『z』『 『A』『 』『B』『 』『C』『 』『D』『 』『E』『 』『F』『 』『G』『 』『H』『 』『I』『 』『J』『 』『K』『 』『L』『 』『M』『 』『N』『 』『O』『 』『P』『 』『Q』『 』『R』『 』『S』『 』『T』『 』『U』『 』『V』『 』『W』『 』『X』『 』『Y』『 』『Z』『-྾a྾ ྾b྾ ྾c྾ ྾d྾ ྾e྾ ྾f྾ ྾g྾ ྾h྾ ྾i྾ ྾j྾ ྾k྾ ྾l྾ ྾m྾ ྾n྾ ྾o྾ ྾p྾ ྾q྾ ྾r྾ ྾s྾ ྾t྾ ྾u྾ ྾v྾ ྾w྾ ྾x྾ ྾y྾ ྾z྾ ྾A྾ ྾B྾ ྾C྾ ྾D྾ ྾E྾ ྾F྾ ྾G྾ ྾H྾ ྾I྾ ྾J྾ ྾K྾ ྾L྾ ྾M྾ ྾N྾ ྾O྾ ྾P྾ ྾Q྾ ྾R྾ ྾S྾ ྾T྾ ྾U྾ ྾V྾ ྾W྾ ྾X྾ ྾Y྾ ྾Z྾-ᆖaᆖ ᆖbᆖ ᆖcᆖ ᆖdᆖ ᆖeᆖ ᆖfᆖ ᆖgᆖ ᆖhᆖ ᆖiᆖ ᆖjᆖ ᆖkᆖ ᆖlᆖ ᆖmᆖ ᆖnᆖ ᆖoᆖ ᆖpᆖ ᆖqᆖ ᆖrᆖ ᆖsᆖ ᆖtᆖ ᆖuᆖ ᆖvᆖ ᆖwᆖ ᆖxᆖ ᆖyᆖ ᆖzᆖ ᆖAᆖ ᆖBᆖ ᆖCᆖ ᆖDᆖ ᆖEᆖ ᆖFᆖ ᆖGᆖ ᆖHᆖ ᆖIᆖ ᆖJᆖ ᆖKᆖ ᆖLᆖ ᆖMᆖ ᆖNᆖ ᆖOᆖ ᆖPᆖ ᆖQᆖ ᆖRᆖ ᆖSᆖ ᆖTᆖ ᆖUᆖ ᆖVᆖ ᆖWᆖ ᆖXᆖ ᆖYᆖ ᆖZᆖ-▓𝗮▓ ▓𝗯▓ ▓𝗰▓ ▓𝗱▓ ▓𝗲▓ ▓𝗳▓ ▓𝗴▓ ▓𝗵▓ ▓𝗶▓ ▓𝗷▓ ▓𝗸▓ ▓𝗹▓ ▓𝗺▓ ▓𝗻▓ ▓𝗼▓ ▓𝗽▓ ▓𝗾▓ ▓𝗿▓ ▓𝘀▓ ▓𝘁▓ ▓𝘂▓ v ▓𝘄▓ ▓𝘅▓ ▓𝘆▓ ▓𝘇▓ ▓𝗔▓ ▓𝗕▓ ▓𝗖▓ ▓𝗗▓ ▓𝗘▓ ▓𝗙▓ ▓𝗚▓ ▓𝗛▓ ▓𝗜▓ ▓𝗝▓ ▓𝗞▓ ▓𝗟▓ ▓𝗠▓ ▓𝗡▓ ▓𝗢▓ ▓𝗣▓ ▓𝗤▓ ▓𝗥▓ ▓𝗦▓ ▓𝗧▓ ▓𝗨▓ V ▓𝗪▓ ▓𝗫▓ ▓𝗬▓ ▓𝗭▓-҈҉҈҉a҈҉҈҉ ҈҉҈҉b҈҉҈҉ ҈҉҈҉c҈҉҈҉ ҈҉҈҉d҈҉҈҉ ҈҉҈҉e҈҉҈҉ ҈҉҈҉f҈҉҈҉ ҈҉҈҉g҈҉҈҉ ҈҉҈҉h҈҉҈҉ ҈҉҈҉i҈҉҈҉ ҈҉҈҉j҈҉҈҉ ҈҉҈҉k҈҉҈҉ ҈҉҈҉l҈҉҈҉ ҈҉҈҉m҈҉҈҉ ҈҉҈҉n҈҉҈҉ ҈҉҈҉o҈҉҈҉ ҈҉҈҉p҈҉҈҉ ҈҉҈҉q҈҉҈҉ ҈҉҈҉r҈҉҈҉ ҈҉҈҉s҈҉҈҉ ҈҉҈҉t҈҉҈҉ ҈҉҈҉u҈҉҈҉ ҈҉҈҉v҈҉҈҉ ҈҉҈҉w҈҉҈҉ ҈҉҈҉x҈҉҈҉ ҈҉҈҉y҈҉҈҉ ҈҉҈҉z҈҉҈҉ ҈҉҈҉A҈҉҈҉ ҈҉҈҉B҈҉҈҉ ҈҉҈҉C҈҉҈҉ ҈҉҈҉D҈҉҈҉ ҈҉҈҉E҈҉҈҉ ҈҉҈҉F҈҉҈҉ ҈҉҈҉G҈҉҈҉ ҈҉҈҉H҈҉҈҉ ҈҉҈҉I҈҉҈҉ ҈҉҈҉J҈҉҈҉ ҈҉҈҉K҈҉҈҉ ҈҉҈҉L҈҉҈҉ ҈҉҈҉M҈҉҈҉ ҈҉҈҉N҈҉҈҉ ҈҉҈҉O҈҉҈҉ ҈҉҈҉P҈҉҈҉ ҈҉҈҉Q҈҉҈҉ ҈҉҈҉R҈҉҈҉ ҈҉҈҉S҈҉҈҉ ҈҉҈҉T҈҉҈҉ ҈҉҈҉U҈҉҈҉ ҈҉҈҉V҈҉҈҉ ҈҉҈҉W҈҉҈҉ ҈҉҈҉X҈҉҈҉ ҈҉҈҉Y҈҉҈҉ ҈҉҈҉Z҈҉҈҉-a͜͡ b͜͡ c͜͡ d͜͡ e͜͡ f͜͡ g͜͡ h͜͡ i͜͡ j͜͡ k͜͡ l͜͡ m͜͡ n͜͡ o͜͡ p͜͜͡͡ q͜͡ r͜͡ s͜͡ t͜͡ u͜͡ v w͜͡ x͜͡ y͜͡ z͜͡ A͜͡ B͜͡ C͜͡ D͜͡ E͜͡ F͜͡ G͜͡ H͜͡ I͜͡ J͜͡ K͜͡ L͜͡ M͜͡ N͜͡ O͜͡ P͜͜͡͡ Q͜͡ R͜͡ S͜͡ T͜͡ U͜͡ V W͜͡ X͜͡ Y͜͡ Z͜͡-͛a͛ ͛b͛ ͛c͛ ͛d͛ ͛e͛ ͛f͛ ͛g͛ ͛h͛ ͛i͛ ͛j͛ ͛k͛ ͛l͛ ͛m͛ ͛n͛ ͛o͛ ͛p͛ ͛q͛ ͛r͛ ͛s͛ ͛t͛ ͛u͛ ͛v͛ ͛w͛ ͛x͛ ͛y͛ ͛z͛ A͛ ͛B͛ ͛C͛ ͛D͛ ͛E͛ ͛F͛ ͛G͛ ͛H͛ ͛I͛ ͛J͛ ͛K͛ ͛L͛ ͛M͛ ͛N͛ ͛O͛ ͛P͛ ͛Q͛ ͛R͛ ͛S͛ ͛T͛ ͛U͛ ͛V͛ ͛W͛ ͛X͛ ͛Y͛ ͛Z͛-࿚a࿚ ࿚b࿚ ࿚c࿚ ࿚d࿚ ࿚e࿚ ࿚f࿚ ࿚g࿚ ࿚h࿚ ࿚i࿚ ࿚j࿚ ࿚k࿚ ࿚l࿚ ࿚m࿚ ࿚n࿚ ࿚o࿚ ࿚p࿚ ࿚q࿚ ࿚r࿚ ࿚s࿚ ࿚t࿚ ࿚u࿚ ࿚v࿚ ࿚w࿚ ࿚x࿚ ࿚y࿚ ࿚z࿚ ࿚A࿚ ࿚B࿚ ࿚C࿚ ࿚D࿚ ࿚E࿚ ࿚F࿚ ࿚G࿚ ࿚H࿚ ࿚I࿚ ࿚J࿚ ࿚K࿚ ࿚L࿚ ࿚M࿚ ࿚N࿚ ࿚O࿚ ࿚P࿚ ࿚Q࿚ ࿚R࿚ ࿚S࿚ ࿚T࿚ ࿚U࿚ ࿚V࿚ ࿚W࿚ ࿚X࿚ ࿚Y࿚ ࿚Z࿚-꙲꙲a꙲꙲ ꙲꙲b꙲꙲ ꙲꙲c꙲꙲ ꙲꙲d꙲꙲ ꙲꙲e꙲꙲ ꙲꙲f꙲꙲ ꙲꙲g꙲꙲ ꙲꙲h꙲꙲ ꙲꙲i꙲꙲ ꙲꙲j꙲꙲ ꙲꙲k꙲꙲ ꙲꙲l꙲꙲ ꙲꙲m꙲꙲ ꙲꙲n꙲꙲ ꙲꙲o꙲꙲ ꙲꙲p꙲꙲ ꙲꙲q꙲꙲ ꙲꙲r꙲꙲ ꙲꙲s꙲꙲ ꙲꙲t꙲꙲ ꙲꙲u꙲꙲ ꙲꙲v꙲꙲ ꙲꙲w꙲꙲ ꙲꙲x꙲꙲ ꙲꙲y꙲꙲ ꙲꙲z꙲꙲ A꙲꙲ ꙲꙲B꙲꙲ ꙲꙲C꙲꙲ ꙲꙲D꙲꙲ ꙲꙲E꙲꙲ ꙲꙲F꙲꙲ ꙲꙲G꙲꙲ ꙲꙲H꙲꙲ ꙲꙲I꙲꙲ ꙲꙲J꙲꙲ ꙲꙲K꙲꙲ ꙲꙲L꙲꙲ ꙲꙲M꙲꙲ ꙲꙲N꙲꙲ ꙲꙲O꙲꙲ ꙲꙲P꙲꙲ ꙲꙲Q꙲꙲ ꙲꙲R꙲꙲ ꙲꙲S꙲꙲ ꙲꙲T꙲꙲ ꙲꙲U꙲꙲ ꙲꙲V꙲꙲ ꙲꙲W꙲꙲ ꙲꙲X꙲꙲ ꙲꙲Y꙲꙲ ꙲꙲Z꙲꙲-͛͛͛a͛͛͛ ͛͛͛b͛͛͛ ͛͛͛c͛͛͛ ͛͛͛d͛͛͛ ͛͛͛e͛͛͛ ͛͛͛f͛͛͛ ͛͛͛g͛͛͛ ͛͛͛h͛͛͛ ͛͛͛i͛͛͛ ͛͛͛j͛͛͛ ͛͛͛k͛͛͛ ͛͛͛l͛͛͛ ͛͛͛m͛͛͛ ͛͛͛n͛͛͛ ͛͛͛o͛͛͛ ͛͛͛p͛͛͛ ͛͛͛q͛͛͛ ͛͛͛r͛͛͛ ͛͛͛s͛͛͛ ͛͛͛t͛͛͛ ͛͛͛u͛͛͛ ͛͛͛v͛͛͛ ͛͛͛w͛͛͛ ͛͛͛x͛͛͛ ͛͛͛y͛͛͛ ͛͛͛z͛͛͛ ͛͛͛A͛͛͛ ͛͛͛B͛͛͛ ͛͛͛C͛͛͛ ͛͛͛D͛͛͛ ͛͛͛E͛͛͛ ͛͛͛F͛͛͛ ͛͛͛G͛͛͛ ͛͛͛H͛͛͛ ͛͛͛I͛͛͛ ͛͛͛J͛͛͛ ͛͛͛K͛͛͛ ͛͛͛L͛͛͛ ͛͛͛M͛͛͛ ͛͛͛N͛͛͛ ͛͛͛O͛͛͛ ͛͛͛P͛͛͛ ͛͛͛Q͛͛͛ ͛͛͛R͛͛͛ ͛͛͛S͛͛͛ ͛͛͛T͛͛͛ ͛͛͛U͛͛͛ ͛͛͛V͛͛͛ ͛͛͛W͛͛͛ ͛͛͛X͛͛͛ ͛͛͛Y͛͛͛ ͛͛͛Z͛͛͛-̊⫶å⫶ ̊⫶b̊⫶ ̊⫶c̊⫶ ̊⫶d̊⫶ ̊⫶e̊⫶ ̊⫶f̊⫶ ̊⫶g̊⫶ ̊⫶h̊⫶ ̊⫶i̊⫶ ̊⫶j̊⫶ ̊⫶k̊⫶ ̊⫶l̊⫶ ̊⫶m̊⫶ ̊⫶n̊⫶ ̊⫶o̊⫶ ̊⫶p̊⫶ ̊⫶q̊⫶ ̊⫶r̊⫶ ̊⫶s̊⫶ ̊⫶t̊⫶ ̊⫶ů⫶ ̊⫶v̊⫶ ̊⫶ẘ⫶ ̊⫶x̊⫶ ̊⫶ẙ⫶ ̊⫶z̊⫶ ̊⫶Å⫶ ̊⫶B̊⫶ ̊⫶C̊⫶ ̊⫶D̊⫶ ̊⫶E̊⫶ ̊⫶F̊⫶ ̊⫶G̊⫶ ̊⫶H̊⫶ ̊⫶I̊⫶ ̊⫶J̊⫶ ̊⫶K̊⫶ ̊⫶L̊⫶ ̊⫶M̊⫶ ̊⫶N̊⫶ ̊⫶O̊⫶ ̊⫶P̊⫶ ̊⫶Q̊⫶ ̊⫶R̊⫶ ̊⫶S̊⫶ ̊⫶T̊⫶ ̊⫶Ů⫶ ̊⫶V̊⫶ ̊⫶W̊⫶ ̊⫶X̊⫶ ̊⫶Y̊⫶ ̊⫶Z̊⫶-a҉ b҉ c҉ d҉ e҉ f҉ g҉ h҉ i҉ j҉ k҉ l҉ m҉ n҉ o҉ p҉ q҉ r҉ s҉ t҉ u҉ v҉ w҉ x҉ y҉ z҉ A҉ B҉ C҉ D҉ E҉ F҉ G҉ H҉ I҉ J҉ K҉ L҉ M҉ N҉ O҉ P҉ Q҉ R҉ S҉ T҉ U҉ V҉ W҉ X҉ Y҉ Z҉-ⓐ ⓑ ⓒ ⓓ ⓔ ⓕ ⓖ ⓗ ⓘ ⓙ ⓚ ⓛ ⓜ ⓝ ⓞ ⓟ ⓠ ⓡ ⓢ ⓣ ⓤ ⓥ ⓦ ⓧ ⓨ ⓩ Ⓐ Ⓑ Ⓒ Ⓓ Ⓔ Ⓕ Ⓖ Ⓗ Ⓘ Ⓙ Ⓚ Ⓛ Ⓜ Ⓝ Ⓞ Ⓟ Ⓠ Ⓡ Ⓢ Ⓣ Ⓤ Ⓥ Ⓦ Ⓧ Ⓨ Ⓩ-ᴀ ʙ ᴄ ᴅ ᴇ ғ ɢ ʜ ɪ ᴊ ᴋ ʟ ᴍ ɴ ᴏ ᴘ ǫ ʀ s ᴛ ᴜ ᴠ ᴡ x ʏ ᴢ ᴀ ʙ ᴄ ᴅ ᴇ ғ ɢ ʜ ɪ ᴊ ᴋ ʟ ᴍ ɴ ᴏ ᴘ ǫ ʀ s ᴛ ᴜ ᴠ ᴡ x ʏ ᴢ-𝕒 𝕓 𝕔 𝕕 𝕖 𝕗 𝕘 𝕙 𝕚 𝕛 𝕜 𝕝 𝕞 𝕟 𝕠 𝕡 𝕢 𝕣 𝕤 𝕥 𝕦 𝕧 𝕨 𝕩 𝕪 𝕫 𝔸 𝔹 ℂ 𝔻 𝔼 𝔽 𝔾 ℍ 𝕀 𝕁 𝕂 𝕃 𝕄 ℕ 𝕆 ℙ ℚ ℝ 𝕊 𝕋 𝕌 𝕍 𝕎 𝕏 𝕐 ℤ-a̶ b̶ c̶ d̶ e̶ f̶ g̶ h̶ i̶ j̶ k̶ l̶ m̶ n̶ o̶ p̶ q̶ r̶ s̶ t̶ u̶ v̶ w̶ x̶ y̶ z̶ A̶ B̶ C̶ D̶ E̶ F̶ G̶ H̶ I̶ J̶ K̶ L̶ M̶ N̶ O̶ P̶ Q̶ R̶ S̶ T̶ U̶ V̶ W̶ X̶ Y̶ Z̶-a͟ b͟ c͟ d͟ e͟ f͟ g͟ h͟ i͟ j͟ k͟ l͟ m͟ n͟ o͟ p͟ q͟ r͟ s͟ t͟ u͟ v͟ w͟ x͟ y͟ z͟ A͟ B͟ C͟ D͟ E͟ F͟ G͟ H͟ I͟ J͟ K͟ L͟ M͟ N͟ O͟ P͟ Q͟ R͟ S͟ T͟ U͟ V͟ W͟ X͟ Y͟ Z͟-𝔞 𝔟 𝔠 𝔡 𝔢 𝔣 𝔤 𝔥 𝔦 𝔧 𝔨 𝔩 𝔪 𝔫 𝔬 𝔭 𝔮 𝔯 𝔰 𝔱 𝔲 𝔳 𝔴 𝔵 𝔶 𝔷 𝔄 𝔅 ℭ 𝔇 𝔈 𝔉 𝔊 ℌ ℑ 𝔍 𝔎 𝔏 𝔐 𝔑 𝔒 𝔓 𝔔 ℜ 𝔖 𝔗 𝔘 𝔙 𝔚 𝔛 𝔜 ℨ-', str_repeat('a-b-c-d-e-f-g-h-i-j-k-l-m-n-o-p-q-r-s-t-u-v-w-x-y-z-a-b-c-d-e-f-g-h-i-j-k-l-m-n-o-p-q-r-s-t-u-v-w-x-y-z-', 33).'a-b-c-d-e-f-g-h-i-j-k-l-m-n-o-p-q-r-s-t-u-v-w-x-y-z-a-b-c-d-e-f-g-h-i-j-k-l-m-n-o-p-q-r-s-t-u-v-w-x-y-z'],
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
        $this->assertSame($expected, $this->slugify->slugify($actual));
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
        $this->assertSame($expected, $this->slugify->slugify($actual, '__'));
    }

    /**
     * @covers \Cocur\Slugify\Slugify::slugify()
     */
    public function testSlugifyOptionsArray()
    {
        $this->assertSame('file-name', $this->slugify->slugify('file name'));
        $this->assertSame('file+name', $this->slugify->slugify('file name', ['separator' => '+']));

        $this->assertSame('name-1', $this->slugify->slugify('name(1)'));
        $this->assertSame('name(1)', $this->slugify->slugify('name(1)', ['regexp' => '/([^a-z0-9.()]|-)+/']));

        $this->assertSame('file-name', $this->slugify->slugify('FILE NAME'));
        $this->assertSame('FILE-NAME', $this->slugify->slugify('FILE NAME', ['lowercase' => false]));

        $this->assertSame('file-name', $this->slugify->slugify('file name '));
        $this->assertSame('file-name-', $this->slugify->slugify('file name ', ['trim' => false]));

        $this->assertSame('file-name', $this->slugify->slugify('<file name'));
        $this->assertSame('p-file-p-foo-a-href-bar-name-a', $this->slugify->slugify('<p>file</p><!-- foo --> <a href="#bar">name</a>'));
        $this->assertSame('file-name', $this->slugify->slugify('<p>file</p><!-- foo --> <a href="#bar">name</a>', ['strip_tags' => true]));
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
            ['יאַן אַ טאָן יאָ אי רבֿ גיב דו האַװ האַוו יױרן יוירן אַזױ אַזוי יום־כּיפּור חנוכּה יײַכל מײַן בלײך ניי יע ייִדיש פֿליִען צוך סם פ קץ תּורת־אמת', 'yan-a-ton-yo-i-rv-gib-du-hav-hav-yoyrn-yoyrn-azoy-azoy-yum-kipur-khnukh-yaykhl-mayn-bleykh-ney-ye-yidish-flien-tsukh-sm-ph-kts-turs-ms'],
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

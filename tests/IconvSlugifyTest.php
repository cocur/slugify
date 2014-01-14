<?php
namespace Cocur\Slugify\Tests;

use Cocur\Slugify\IconvSlugify;
use PHPUnit_Framework_TestCase;

class IconvSlugifyTest extends PHPUnit_Framework_TestCase
{
    private $slugify;

    public function setUp()
    {
        if (!function_exists('iconv')) {
            $this->markTestSkipped('intl extension not loaded');
        }

        $this->slugify = new IconvSlugify();
    }

    /**
     * @dataProvider provider
     */
    public function testSlugify($string, $result)
    {
        $this->assertEquals($result, $this->slugify->slugify($string));
    }

    public function testCustomRules()
    {
        $this->slugify->outCharset = 'ASCII//IGNORE';
        $this->assertEquals('', $this->slugify->slugify('€'));
    }

    public function provider()
    {
        return array(
            array('Hello', 'hello'),
            array('[0]', '0'),
            array('Hello World', 'hello-world'),
            array('Hello: World', 'hello-world'),
            // does not seem to work consistently
            #array('H+e#l1l--o/W§o r.l:d)', 'h-e-l1l-o-wsso-r-l-d'),
            array(': World', 'world'),
            array('Hello World!', 'hello-world'),
            array('Ä ä Ö ö Ü ü ß', 'a-a-o-o-u-u-ss'),
            array('Á À á à É È é è Ó Ò ó ò Ñ ñ Ú Ù ú ù', 'a-a-a-a-e-e-e-e-o-o-o-o-n-n-u-u-u-u'),
            array('Â â Ê ê Ô ô Û û', 'a-a-e-e-o-o-u-u'),
            array('Â â Ê ê Ô ô Û 1', 'a-a-e-e-o-o-u-1'),
            //this doesnt work with iconv
            #array('°¹²³@','0123at'),
            //german translit, ø doesnt work: iconv bug
            #array('Mórë thån wørds', 'more-thaan-words')
        );
    }
}
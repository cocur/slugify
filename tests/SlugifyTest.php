<?php
namespace Cocur\Slugify\Tests;

use Cocur\Slugify\Slugify;
use PHPUnit_Framework_TestCase;


class SlugifyTest extends PHPUnit_Framework_TestCase
{
    private $slugify;

    public function setUp()
    {
        $this->slugify = new Slugify();
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
        $this->slugify->rules['X'] = 'y';
        $this->assertEquals('y', $this->slugify->slugify('X'));
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
            array('Блоґ їжачка', 'blog-jizhachka')
        );
    }
}

<?php
namespace Cocur\Tests\Slugify;

use Cocur\Slugify\Slugify;

class SlugifyIconvTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provider
     */
    public function testSlugify($string, $slug)
    {
        if (!function_exists('iconv')) {
            $this->markTestSkipped('The "iconv" function is not available.');
            return;
        }
        //we assume german locale
        setlocale(LC_ALL, 'de_DE.utf8', 'de_DE');
        $slugify = new Slugify();
        $this->assertEquals($slug, $slugify->slugify($string), '->slugify() transforms the string in a correct slug.');
    }

    public function provider()
    {
        return array(
            array('Hello', 'hello'),
            array('[0]', '0'),
            array('Hello World', 'hello-world'),
            array('Hello: World', 'hello-world'),
            array(': World', 'world'),
            array('Hello World!', 'hello-world'),
            array('Ä ä Ö ö Ü ü ß', 'a-a-o-o-u-u-ss'),
            array('Á À á à É È é è Ó Ò ó ò Ñ ñ Ú Ù ú ù', 'a-a-a-a-e-e-e-e-o-o-o-o-n-n-u-u-u-u'),
            array('Â â Ê ê Ô ô Û û', 'a-a-e-e-o-o-u-u'),
            array('Â â Ê ê Ô ô Û 1', 'a-a-e-e-o-o-u-1'),
        );
    }
}

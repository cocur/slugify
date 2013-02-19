<?php

/**
 * The MIT License (MIT)
 * Copyright (c) 2012 Florian Eckerstorfer
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to
 * deal in the Software without restriction, including without limitation the
 * rights to use, copy, modify, merge, publish, distribute, sublicense, and/or
 * sell copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS
 * IN THE SOFTWARE.
 *
 * @package   org.cocur.slugify
 * @category  tests
 */

namespace Cocur\Tests\Slugify;

use Cocur\Slugify\Slugify;

require_once __DIR__ . '/../../../../src/Cocur/Slugify/Slugify.php';

/**
 * @package   org.cocur.slugify
 * @category  tests
 * @author    Ivo Bathke <ivo.bathke@gmail.com>
 * @author    Florian Eckerstorfer <florian@theroadtojoy.at>
 * @copyright 2012 Florian Eckerstorfer
 * @license   http://www.opensource.org/licenses/MIT The MIT License
 */
class SlugifyIconvTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @param string $string
     * @param string $slug
     * @dataProvider provider
     */
    public function testSlugify($string, $slug) {
        //we assume german locale
        setlocale(LC_ALL, 'de_DE.utf8','de_DE');
        $slugify = new Slugify();
        $this->assertEquals($slug, $slugify->slugify($string), '->slugify() transforms the string in a correct slug.');
    }

    /**
     * @return array
     */
    public function provider()
    {
        return array(
            array('Hello', 'hello'),
            array('Hello World', 'hello-world'),
            array('Hello: World', 'hello-world'),
            array(': World', 'world'),
            array('Hello World!', 'hello-world'),
            array('Ä ä Ö ö Ü ü ß', 'ae-ae-oe-oe-ue-ue-ss'),
            array('Á À á à É È é è Ó Ò ó ò Ñ ñ Ú Ù ú ù', 'a-a-a-a-e-e-e-e-o-o-o-o-n-n-u-u-u-u'),
            array('Â â Ê ê Ô ô Û û', 'a-a-e-e-o-o-u-u'),
            array('Â â Ê ê Ô ô Û 1', 'a-a-e-e-o-o-u-1'),
            array('°¹²³@','0123at'),
            //german translit, ø doesnt work: iconv bug
            #array('Mórë thån wørds', 'more-thaan-words')
        );
    }

}

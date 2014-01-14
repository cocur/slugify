<?php
namespace Cocur\Slugify\Tests;

use Cocur\Slugify\Slugify;
use PHPUnit_Framework_TestCase;


class SlugifyArrayMock extends Slugify
{
    public function isIntlAvailable()
    {
        return false;
    }

    public function isIconvAvailable()
    {
        return false;
    }
}

class SlugifyIconvMock extends Slugify
{
    protected function isIntlAvailable()
    {
        return false;
    }
}

class SlugifyTest extends PHPUnit_Framework_TestCase
{
    private $slugify;

    public function setUp()
    {
        $this->slugify = new Slugify();
    }

    public function testSlugify()
    {
        $slugify = new Slugify();
        $this->assertInstanceOf('Cocur\\Slugify\\SlugifyInterface', $slugify);
        $this->assertEquals('hello-world', $slugify->slugify('Hello World'));

        $slugify = new SlugifyArrayMock();
        $this->assertInstanceOf('Cocur\\Slugify\\SlugifyInterface', $slugify);
        $this->assertEquals('hello-world', $slugify->slugify('Hello World'));


        $slugify = new SlugifyIconvMock();
        $this->assertInstanceOf('Cocur\\Slugify\\SlugifyInterface', $slugify);
        $this->assertEquals('hello-world', $slugify->slugify('Hello World'));
    }

    public function testStatic()
    {
        $this->assertInstanceOf('Cocur\\Slugify\\SlugifyInterface', Slugify::create());
        $this->assertEquals('hello-world', Slugify::create()->slugify('Hello World'));
    }

}

<?php
namespace Cocur\Slugify;

class Slugify implements SlugifyInterface
{
    /**
     * @var SlugifyInterface
     */
    private $slugify;

    public function __construct()
    {
        if ($this->isIntlAvailable()) {
            $this->slugify = new NativeSlugify();
        } elseif ($this->isIconvAvailable()) {
            $this->slugify = new IconvSlugify();
        } else {
            $this->slugify = new ArraySlugify();
        }
    }

    protected function isIntlAvailable()
    {
        return extension_loaded('intl') && function_exists('transliterator_transliterate');
    }

    protected function isIconvAvailable()
    {
        return function_exists('iconv');
    }

    /**
     * Static method to create new instance of {@see Slugify}.
     *
     * @return Slugify
     */
    public static function create()
    {
        return new static();
    }

    public function slugify($string, $separator = '-')
    {
        return $this->slugify->slugify($string, $separator);
    }
}

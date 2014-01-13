<?php
namespace Cocur\Slugify;

interface SlugifyInterface
{
    /**
     * Return a URL safe version of a string.
     *
     * @param string $string
     * @return string
     */
    public function slugify($string);
}

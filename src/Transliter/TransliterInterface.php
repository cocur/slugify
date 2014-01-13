<?php

namespace Cocur\Slugify\Transliter;

/**
* TransliterInterface
*/
interface TransliterInterface
{
    /**
     * Translits the given input string.
     *
     * @param string $input Input string.
     *
     * @return string Translited string.
     */
    public function translit($input);
}

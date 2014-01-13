<?php

namespace Cocur\Slugify\Transliter;

use Cocur\Slugify\Slugify;

/**
 * TransliterFactory
 */
class TransliterFactory
{
    /**
     * Returns a new instance of a transliter with the given mode.
     *
     * @param string $transliter Name of the transliter
     *
     * @return TransliterInterface Transliter
     */
    public static function create($transliter)
    {
        switch ($transliter) {
            case Slugify::MODE_ICONV:
                return new IconvTransliter();

            case Slugify::MODE_ARRAYMAP:
                return new ArrayMapTransliter();

            default:
                throw new \InvalidArgumentException(sprintf('Unkown transliter "%s".', $transliter));
        }
    }
}

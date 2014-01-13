<?php

namespace Cocur\Slugify\Transliter;

/**
 * IconvTransliter
 */
class IconvTransliter implements TransliterInterface
{
    /**
     * Transliterate the string by using the iconv extension.
     *
     * Needs locale to be set for country specific transliteration:
     *
     *   <?php setlocale(LC_ALL, 'de_DE.utf8','de_DE'); ?>
     *
     * Taken form doctrine project
     *
     * @param string $string
     *
     * @return string
     */
    public function translit($input)
    {
        return iconv('utf-8', 'us-ascii//TRANSLIT', $input);
    }
}

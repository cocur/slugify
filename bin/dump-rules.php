<?php

require_once __DIR__ . '/vendor/autoload.php';

$languages = \voku\helper\ASCII::getAllLanguages();

foreach ($languages as $languageName => $language) {
    $langSpecific = \voku\helper\ASCII::charsArrayWithOneLanguage($language, false, false);
    if (count($langSpecific) === 0) {
        continue;
    }

    // mapping
    if ($languageName === 'portuguese') {
        $languageName = 'portuguese-brazil';
    }
    if ($languageName === 'myanmar') {
        $languageName = 'burmese';
    }
    if ($languageName === 'german_austrian') {
        $languageName = 'austrian';
    }
    if ($languageName === 'russian') {
        continue;
    }
    if ($languageName === 'ru__gost_2000_b') {
        $languageName = 'russian';
    }

    $json = json_encode($langSpecific, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    $file = __DIR__ . '/../Resources/rules/' . $languageName . '.json';

    if (!file_exists($file)) {
        echo "warning: " . $file . ' did not exists, mapping needed?' . "\n";
        continue;
    }

    file_put_contents(__DIR__ . '/../Resources/rules/' . $languageName . '.json', $json);
}


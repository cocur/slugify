<?php

$iterator = new DirectoryIterator(__DIR__ . '/../Resources/rules/');

foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'json') {
        $array = json_decode(file_get_contents($file->getRealPath()), true);
        ksort($array);
        $json = json_encode($array, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        file_put_contents($file->getRealPath(), $json);
    }
}

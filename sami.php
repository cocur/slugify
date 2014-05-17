<?php

use Sami\Sami;
use Sami\Version\GitVersionCollection;

$dir = __DIR__.'/src';

$versions = GitVersionCollection::create($dir)
    ->addFromTags('v0.*')
    ->add('master', 'master branch')
;

return new Sami($dir, array(
    'title'                => 'Slugify API',
    'theme'                => 'enhanced',
    'versions'             => $versions,
    'build_dir'            => __DIR__.'/build/api/%version%',
    'cache_dir'            => __DIR__.'/cache/api/%version%',
    'default_opened_level' => 2,
));

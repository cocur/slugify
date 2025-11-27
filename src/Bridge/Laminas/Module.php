<?php

namespace Cocur\Slugify\Bridge\Laminas;

/**
 * Class Module
 * @package    cocur/slugify
 * @subpackage bridge
 * @license    http://www.opensource.org/licenses/MIT The MIT License
 */
class Module
{
    public const CONFIG_KEY = 'cocur_slugify';

    public function getConfig(): array
    {
        $provider                  = new ConfigProvider();
        $config                    = $provider();
        $config['service_manager'] = $config['dependencies'];
        unset($config['dependencies']);

        return $config;
    }
}

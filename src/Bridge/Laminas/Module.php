<?php

namespace Cocur\Slugify\Bridge\Laminas;

use Cocur\Slugify\Slugify;
use Laminas\ModuleManager\Feature\ServiceProviderInterface;
use Laminas\ModuleManager\Feature\ViewHelperProviderInterface;
use Laminas\ServiceManager\Config;

/**
 * Class Module
 * @package    cocur/slugify
 * @subpackage bridge
 * @license    http://www.opensource.org/licenses/MIT The MIT License
 */
class Module
{
    public const CONFIG_KEY = 'cocur_slugify';

    public function getConfig()
    {
        $provider = new ConfigProvider();
        return [
            'dependencies' => $provider->getDependencyConfig(),
            'view_helpers' => $provider->getViewHelperConfig(),
        ];
    }
}

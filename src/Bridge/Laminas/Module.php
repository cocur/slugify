<?php

namespace Cocur\Slugify\Bridge\Laminas;

use Cocur\Slugify\Slugify;
use Laminas\ModuleManager\Feature\ServiceProviderInterface;
use Laminas\ModuleManager\Feature\ViewHelperProviderInterface;
use Zend\ServiceManager\Config;

/**
 * Class Module
 * @package    cocur/slugify
 * @subpackage bridge
 * @license    http://www.opensource.org/licenses/MIT The MIT License
 */
class Module implements ServiceProviderInterface, ViewHelperProviderInterface
{
    const CONFIG_KEY = 'cocur_slugify';

    /**
     * Expected to return \Laminas\ServiceManager\Config object or array to
     * seed such an object.
     *
     * @return array<string,array<string,string>>
     */
    public function getServiceConfig()
    {
        return [
            'factories' => [
                Slugify::class => SlugifyService::class,
            ],
            'aliases' => [
                'slugify' => Slugify::class,
            ]
        ];
    }

    /**
     * Expected to return \Laminas\ServiceManager\Config object or array to
     * seed such an object.
     *
     * @return array<string,array<string,string>>|Config
     */
    public function getViewHelperConfig()
    {
        return [
            'aliases' => [
                'slugify' => SlugifyViewHelper::class
            ],
            'factories' => [
                SlugifyViewHelper::class => SlugifyViewHelperFactory::class
            ]
        ];
    }
}

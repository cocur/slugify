<?php
namespace Cocur\Slugify\Bridge\ZF2;

use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;

/**
 * Class Module
 * @package    cocur/slugify
 * @subpackage bridge
 * @license    http://www.opensource.org/licenses/MIT The MIT License
 */
class Module implements ConfigProviderInterface, ServiceProviderInterface
{
    const CONFIG_KEY = 'cocur_slugify';

    /**
     * Returns configuration to merge with application configuration
     *
     * @return array|\Traversable
     */
    public function getConfig()
    {
        return array(
            'view_helpers' => array(
                'factories' => array(
                    'slugify' => 'Cocur\Slugify\Bridge\ZF2\SlugifyViewHelperFactory'
                )
            )
        );
    }

    /**
     * Expected to return \Zend\ServiceManager\Config object or array to
     * seed such an object.
     *
     * @return array
     */
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Cocur\Slugify\Slugify' => 'Cocur\Slugify\Bridge\ZF2\SlugifyService'
            ),
            'aliases' => array(
                'slugify' => 'Cocur\Slugify\Slugify'
            )
        );
    }
}
 
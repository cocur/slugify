<?php

namespace Cocur\Slugify\Bridge\Laminas;

use Cocur\Slugify\Slugify;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

/**
 * Class SlugifyService
 * @package    cocur/slugify
 * @subpackage bridge
 * @license    http://www.opensource.org/licenses/MIT The MIT License
 */
class SlugifyService implements FactoryInterface
{

    /**
     * 
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array $options
     * @return object
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): Slugify {
        $config = $container->get('Config');

        $slugifyOptions  = isset($config[Module::CONFIG_KEY]['options']) ? $config[Module::CONFIG_KEY]['options'] : [];
        $provider = isset($config[Module::CONFIG_KEY]['provider']) ? $config[Module::CONFIG_KEY]['provider'] : null;

        return new Slugify($slugifyOptions, $provider);
    }

}

<?php

namespace Cocur\Slugify\Bridge\Laminas;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

/**
 * Class SlugifyViewHelperFactory
 * @package    cocur/slugify
 * @subpackage bridge
 * @license    http://www.opensource.org/licenses/MIT The MIT License
 */
class SlugifyViewHelperFactory implements FactoryInterface
{

    /**
     * 
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array $options
     * @return SlugifyViewHelper
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): SlugifyViewHelper 
    {
        $slugify = $container->get('Cocur\Slugify\Slugify');
        return new SlugifyViewHelper($slugify);
    }

}

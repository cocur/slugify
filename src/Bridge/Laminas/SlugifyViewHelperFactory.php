<?php

namespace Cocur\Slugify\Bridge\Laminas;

use Psr\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * Class SlugifyViewHelperFactory
 * @package    cocur/slugify
 * @subpackage bridge
 * @license    http://www.opensource.org/licenses/MIT The MIT License
 */
class SlugifyViewHelperFactory
{

    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     *
     * @return SlugifyViewHelper
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): SlugifyViewHelper
    {
        $slugify = $container->get('Cocur\Slugify\Slugify');

        return new SlugifyViewHelper($slugify);
    }

}

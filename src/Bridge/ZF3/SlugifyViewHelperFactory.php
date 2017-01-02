<?php

namespace Cocur\Slugify\Bridge\ZF3;

use Cocur\Slugify\Slugify;
use Interop\Container\ContainerInterface;

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
     *
     * @return SlugifyViewHelper
     */
    public function __invoke(ContainerInterface $container)
    {
        /** @var Slugify $slugify */
        $slugify = $container->get('Cocur\Slugify\Slugify');

        return new SlugifyViewHelper($slugify);
    }
}

<?php

namespace Cocur\Slugify\Bridge\Laminas;

use Cocur\Slugify\Slugify;
use Psr\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * Class SlugifyService
 * @package    cocur/slugify
 * @subpackage bridge
 * @license    http://www.opensource.org/licenses/MIT The MIT License
 */
class SlugifyService
{
    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     *
     * @return Slugify
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): Slugify
    {
        $config = $container->get('Config');

        $slugifyOptions  =  $config[Module::CONFIG_KEY]['options'] ?? [];
        $provider = $config[Module::CONFIG_KEY]['provider'] ?? null;

        return new Slugify($slugifyOptions, $provider);
    }

}

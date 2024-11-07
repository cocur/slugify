<?php

namespace Cocur\Slugify\Bridge\Laminas;

use Cocur\Slugify\Slugify;
use Laminas\ServiceManager\Factory\InvokableFactory;

class ConfigProvider
{
    /**
     * Retrieve laminas default configuration.
     *
     * @return array
     */
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencyConfig(),
            'filters'      => $this->filterConfig(),
            'view_helpers' => $this->getViewHelperConfig(),
        ];
    }

    /**
     * Retrieve laminas default dependency configuration.
     *
     * @return array
     */
    public function getDependencyConfig(): array
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
     * Retrieve laminas view helper dependency configuration.
     *
     * @return array
     */
    public function getViewHelperConfig(): array
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

    /**
     * @return array
     */
    private function filterConfig(): array
    {
        return [
            'factories' => [
                SlugifyFilter::class => InvokableFactory::class,
            ],
            'aliases'   => [
                'slugify' => SlugifyFilter::class,
            ],
        ];
    }
}

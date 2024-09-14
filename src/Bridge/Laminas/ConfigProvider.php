<?php

namespace Cocur\Slugify\Bridge\Laminas;

use Cocur\Slugify\Slugify;

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
}

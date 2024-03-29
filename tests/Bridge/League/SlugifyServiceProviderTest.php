<?php

namespace Cocur\Slugify\Tests\Bridge\League;

use Cocur\Slugify\Bridge\League\SlugifyServiceProvider;
use Cocur\Slugify\RuleProvider\DefaultRuleProvider;
use Cocur\Slugify\RuleProvider\RuleProviderInterface;
use Cocur\Slugify\SlugifyInterface;
use League\Container\Container;
use Mockery as m;
use Mockery\Adapter\Phpunit\MockeryTestCase;

class SlugifyServiceProviderTest extends MockeryTestCase
{
    public function testProvidesSlugify()
    {
        $container = new Container();

        $container->addServiceProvider(new SlugifyServiceProvider());

        $slugify = $container->get(SlugifyInterface::class);

        $this->assertInstanceOf(SlugifyInterface::class, $slugify);
        $this->assertInstanceOf(DefaultRuleProvider::class, $this->getProperty($slugify, 'provider'));
    }

    public function testProvidesSlugifyAsSharedService()
    {
        $container = new Container();

        $container->addServiceProvider(new SlugifyServiceProvider());

        $slugify = $container->get(SlugifyInterface::class);

        $this->assertSame($slugify, $container->get(SlugifyInterface::class));
    }

    public function testProvidesSlugifyUsingSharedConfigurationOptions()
    {
        $container = new Container();

        $options = [
            'lowercase' => false,
        ];

        $container->share('config.slugify.options', $options);
        $container->addServiceProvider(new SlugifyServiceProvider());

        /* @var SlugifyInterface $slugify */
        $slugify = $container->get(SlugifyInterface::class);

        $slug = 'Foo-Bar-Baz';

        $this->assertSame($slug, $slugify->slugify($slug));
    }

    public function testProvidesSlugifyUsingSharedProvider()
    {
        $container = new Container();

        $ruleProvider = $this->getRuleProviderMock();

        $container->share(RuleProviderInterface::class, $ruleProvider);
        $container->addServiceProvider(new SlugifyServiceProvider());

        $slugify = $container->get(SlugifyInterface::class);

        $this->assertSame($ruleProvider, $this->getProperty($slugify, 'provider'));
    }

    /**
     * @return m\Mock|RuleProviderInterface
     */
    private function getRuleProviderMock()
    {
        $ruleProvider = m::mock(RuleProviderInterface::class);

        $ruleProvider
            ->shouldReceive('getRules')
            ->withAnyArgs()
            ->andReturn([])
        ;

        return $ruleProvider;
    }

    private function getProperty(SlugifyInterface $slugify, string $name)
    {
        $reflection = new \ReflectionClass($slugify);
        $prop = $reflection->getProperty($name);
        $prop->setAccessible(true);

        return $prop->getValue($slugify);
    }
}

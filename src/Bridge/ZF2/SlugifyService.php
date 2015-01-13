<?php

namespace Cocur\Slugify\Bridge\ZF2;

use Cocur\Slugify\Slugify;
use Zend\ServiceManager\ServiceManager;

/**
 * Class SlugifyService
 * @package    cocur/slugify
 * @subpackage bridge
 * @license    http://www.opensource.org/licenses/MIT The MIT License
 */
class SlugifyService
{
    /**
     * @param ServiceManager $sm
     *
     * @return Slugify
     */
    public function __invoke($sm)
    {
        $config = $sm->get('Config');

        if (isset($config[Module::CONFIG_KEY]) && isset($config[Module::CONFIG_KEY]['reg_exp'])) {
            return new Slugify($config[Module::CONFIG_KEY]['reg_exp']);
        } else {
            return new Slugify();
        }
    }
}

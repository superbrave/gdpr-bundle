<?php
/**
 * This file initializes the GDPR bundle
 *
 * Minimal required PHP version is 5.6
 *
 * @category  Bundle
 * @package   Gdpr
 * @author    Superbrave <info@superbrave.nl>
 * @copyright 2018 Superbrave <info@superbrave.nl>
 * @license   https://github.com/superbrave/gdpr-bundle/blob/master/LICENSE MIT
 * @link      https://www.superbrave.nl/
 */

namespace SuperBrave\GdprBundle;

use SuperBrave\GdprBundle\DependencyInjection\Compiler\AddAnonymizersCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class SuperBraveGdprBundle
 */
class SuperBraveGdprBundle extends Bundle
{
    /**
     * Build the bundle and dependencies
     *
     * @param ContainerBuilder $container The service container
     *
     * @return void
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new AddAnonymizersCompilerPass());
    }
}

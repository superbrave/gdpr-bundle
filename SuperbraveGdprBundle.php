<?php
/**
 * This file initializes the GDPR bundle.
 *
 * Minimal required PHP version is 5.6
 *
 * @category  Bundle
 *
 * @author    SuperBrave <info@superbrave.nl>
 * @copyright 2018 SuperBrave <info@superbrave.nl>
 * @license   https://github.com/superbrave/gdpr-bundle/blob/master/LICENSE MIT
 *
 * @see       https://www.superbrave.nl/
 */

namespace Superbrave\GdprBundle;

use Superbrave\GdprBundle\DependencyInjection\Compiler\AddAnonymizersCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class SuperbraveGdprBundle.
 */
class SuperbraveGdprBundle extends Bundle
{
    /**
     * Build the bundle and dependencies.
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

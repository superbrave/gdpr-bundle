<?php
/**
 * This file is part of the GDPR bundle.
 *
 * @category  Bundle
 * @package   Gdpr
 * @author    SuperBrave <info@superbrave.nl>
 * @copyright 2018 SuperBrave <info@superbrave.nl>
 * @license   https://github.com/superbrave/gdpr-bundle/blob/master/LICENSE MIT
 * @link      https://www.superbrave.nl/
 */

namespace Superbrave\GdprBundle\DependencyInjection\Compiler;

use LogicException;
use Superbrave\GdprBundle\Anonymize\Type\AnonymizerInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Compiler pass to add the tagged anonymizers to the anonymizer manager's definition
 *
 * @package SuperBrave\GdprBundle\DependencyInjection\Compiler
 */
class AddAnonymizersCompilerPass implements CompilerPassInterface
{
    /**
     * Gets all the anonymizers from the services tagged with 'superbrave_gdpr.anonymizer'
     * The adds them to the AnonymizerCollection
     *
     * @param ContainerBuilder $container The service container
     *
     * @return void
     *
     * @throws LogicException on invalid interface usage.
     */
    public function process(ContainerBuilder $container)
    {
        if ($container->hasDefinition('superbrave_gdpr.anonymizer_collection') === false) {
            return;
        }

        $anonymizerManagerDefinition = $container->getDefinition('superbrave_gdpr.anonymizer_collection');

        $anonymizers = $container->findTaggedServiceIds('superbrave_gdpr.anonymizer');
        foreach ($anonymizers as $anonymizer => $attributes) {
            $type = $attributes[0]['type'];

            $definition = $container->getDefinition($anonymizer);

            //validate class interface
            $class = $container->getParameterBag()->resolveValue($definition->getClass());
            if (is_subclass_of($class, AnonymizerInterface::class) === false) {
                throw new LogicException(
                    sprintf(
                        '%s should implement the %s interface when used as anonymizer.',
                        $class,
                        AnonymizerInterface::class
                    )
                );
            }

            $anonymizerManagerDefinition->addMethodCall('addAnonymizer', [$type, new Reference($anonymizer)]);
        }
    }
}

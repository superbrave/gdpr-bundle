<?php

namespace SuperBrave\GdprBundle\DependencyInjection\Compiler;

use LogicException;
use SuperBrave\GdprBundle\Anonymizer\AnonymizerInterface;
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
    public function process(ContainerBuilder $container)
    {
        if ($container->hasDefinition('super_brave_gdpr.anonymizer_collection') === false) {
            return;
        }

        $anonymizerManagerDefinition = $container->getDefinition('super_brave_gdpr.anonymizer_collection');

        $anonymizers = $container->findTaggedServiceIds('super_brave_gdpr.anonymizer');
        foreach ($anonymizers as $anonymizer => $attributes) {

            $type = $attributes[0]['type'];

            $definition = $container->getDefinition($anonymizer);

            //validate class interface
            $class = $container->getParameterBag()->resolveValue($definition->getClass());
            if (is_subclass_of($class, AnonymizerInterface::class) === false) {
                throw new LogicException(
                    sprintf('%s should implement the %s interface when used as anonymizer.', $class, AnonymizerInterface::class)
                );
            }

            $anonymizerManagerDefinition->addMethodCall('addAnonymizer', [$type, new Reference($anonymizer)]);
        }
    }
}

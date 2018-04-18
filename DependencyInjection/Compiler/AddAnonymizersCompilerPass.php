<?php

namespace SuperBrave\GdprBundle\DependencyInjection\Compiler;

use LogicException;
use SuperBrave\GdprBundle\Anonymizer\AnonymizerInterface;
use Symfony\Component\DependencyInjection\Argument\IteratorArgument;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\PriorityTaggedServiceTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Compiler pass to add the tagged anonymizers to the anonymizer manager's definition
 *
 * @package SuperBrave\GdprBundle\DependencyInjection\Compiler
 */
class AddAnonymizersCompilerPass implements CompilerPassInterface
{
    use PriorityTaggedServiceTrait;

    public function process(ContainerBuilder $container)
    {
        if ($container->hasDefinition('super_brave_gdpr.anonymizer_manager') === false) {
            return;
        }

        $anonymizers = $this->findAndSortTaggedServices('super_brave_gdpr.anonymizer', $container);
        foreach ($anonymizers as $anonymizer) {
            $definition = $container->getDefinition((string) $anonymizer);
            $class = $container->getParameterBag()->resolveValue($definition->getClass());

            if (is_subclass_of($class, AnonymizerInterface::class) === false) {
                throw new LogicException(
                    sprintf('%s should implement the %s interface when used as anonymizer.', $class, AnonymizerInterface::class)
                );
            }
        }

        $adm = $container->getDefinition('super_brave_gdpr.anonymizer_manager');
        $adm->replaceArgument(0, new IteratorArgument($anonymizers));
    }
}

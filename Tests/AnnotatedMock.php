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

namespace SuperBrave\GdprBundle\Tests;

use Doctrine\Common\Collections\ArrayCollection;
use SuperBrave\GdprBundle\Annotation as GDPR;

/**
 * Class used to test the @see GDPR\AnnotationReader.
 *
 * @author Niels Nijens <nn@superbrave.nl>
 */
class AnnotatedMock
{
    /**
     * The foo property.
     *
     * @GDPR\Export()
     *
     * @var string
     */
    private $foo = 'bar';

    /**
     * The baz property.
     *
     * @GDPR\Export()
     *
     * @var int
     */
    private $baz = 1;

    /**
     * The qux property.
     *
     * @GDPR\Export()
     *
     * @var array
     */
    private $qux = array();

    /**
     * The quux property.
     *
     * @GDPR\Export(alias="quuxs")
     *
     * @var ArrayCollection
     */
    private $quux;

    /**
     * The property that is annotated with the Export annotation, but without getter method.
     *
     * @GDPR\Export()
     *
     * @var bool
     */
    private $annotatedPropertyWithoutMethod = true;

    /**
     * The property that is not annotated with the Export annotation.
     *
     * @var null
     */
    private $notAnnotatedProperty = null;

    /**
     * Constructs a new AnnotatedMock instance.
     *
     * @param AnnotatedMock|null $annotatedMock An AnnotatedMock instance
     */
    public function __construct(AnnotatedMock $annotatedMock = null)
    {
        $elements = array();
        if ($annotatedMock instanceof AnnotatedMock) {
            $elements[] = $annotatedMock;
        }

        $this->quux = new ArrayCollection($elements);
    }

    /**
     * Returns the value of the foo property.
     *
     * @return string
     */
    public function getFoo()
    {
        return $this->foo;
    }

    /**
     * Returns the value of the baz property.
     *
     * @return int
     */
    public function getBaz()
    {
        return $this->baz;
    }

    /**
     * Returns the value of the qux property.
     *
     * @return array
     */
    public function getQux()
    {
        return $this->qux;
    }

    /**
     * Returns the value of the quux property.
     *
     * @return ArrayCollection
     */
    public function getQuux()
    {
        return $this->quux;
    }

    /**
     * Returns the value of the notAnnotatedProperty property.
     *
     * @return null
     */
    public function getNotAnnotatedProperty()
    {
        return $this->notAnnotatedProperty;
    }
}

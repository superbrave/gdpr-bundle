<?php
/**
 * This file is part of the GDPR bundle.
 *
 * @category  Bundle
 *
 * @author    SuperBrave <info@superbrave.nl>
 * @copyright 2018 SuperBrave <info@superbrave.nl>
 * @license   https://github.com/superbrave/gdpr-bundle/blob/master/LICENSE MIT
 *
 * @see       https://www.superbrave.nl/
 */

namespace Superbrave\GdprBundle\Tests;

use Doctrine\Common\Collections\ArrayCollection;
use Superbrave\GdprBundle\Annotation as GDPR;

/**
 * Class used to test the @see GDPR\AnnotationReader.
 *
 * @author Niels Nijens <nn@superbrave.nl>
 * @author Jelle van Oosterbosch <jvo@superbrave.nl>
 */
class AnnotatedMock
{
    /**
     * The foo property.
     *
     * @GDPR\Export()
     * @GDPR\Anonymize(type="fixed", value="foo")
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
    private $qux = [];

    /**
     * The quux property.
     *
     * @GDPR\Export(alias="quuxs")
     *
     * @var ArrayCollection
     */
    private $quux;

    /**
     * The quuz property.
     *
     * @GDPR\Export()
     *
     * @var \DateTime
     */
    private $quuz;

    /**
     * The property that is annotated with the Export annotation, but without getter method.
     *
     * @GDPR\Export(
     *     valueMap={
     *         true = "Yes",
     *         false = "No"
     *     }
     * )
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
        $elements = [];
        if ($annotatedMock instanceof AnnotatedMock) {
            $elements[] = $annotatedMock;
            $elements[] = clone $annotatedMock;
        }

        $this->quuz = new \DateTime('2016/01/01');
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
     * @return \DateTime
     */
    public function getQuuz()
    {
        return $this->quuz;
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

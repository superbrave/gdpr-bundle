<?php

namespace SuperBrave\GdprBundle\Tests;

use Doctrine\Common\Collections\ArrayCollection;
use SuperBrave\GdprBundle\Annotation as GDPR;

/**
 * AnnotatedMock.
 *
 * @author Niels Nijens <nn@superbrave.nl>
 */
class AnnotatedMock
{
    /**
     * @GDPR\Export()
     *
     * @var string
     */
    private $foo = 'bar';

    /**
     * @GDPR\Export()
     *
     * @var int
     */
    private $baz = 1;

    /**
     * @GDPR\Export()
     *
     * @var array
     */
    private $qux = array();

    /**
     * @GDPR\Export()
     *
     * @var ArrayCollection
     */
    private $quux;

    /**
     * Constructs a new AnnotatedMock instance.
     *
     * @param AnnotatedMock|null $annotatedMock
     */
    public function __construct(AnnotatedMock $annotatedMock = null)
    {
        $this->quux = new ArrayCollection(array($annotatedMock));
    }

    /**
     * @return string
     */
    public function getFoo()
    {
        return $this->foo;
    }

    /**
     * @return int
     */
    public function getBaz()
    {
        return $this->baz;
    }

    /**
     * @return array
     */
    public function getQux()
    {
        return $this->qux;
    }

    /**
     * @return ArrayCollection
     */
    public function getQuux()
    {
        return $this->quux;
    }
}

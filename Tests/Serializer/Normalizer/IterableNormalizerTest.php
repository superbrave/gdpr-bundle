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

namespace Superbrave\GdprBundle\Tests\Serializer\Normalizer;

use Doctrine\Common\Collections\ArrayCollection;
use Superbrave\GdprBundle\Serializer\Normalizer\IterableNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * AnnotationNormalizerTest.
 *
 * @author Jelle van Oosterbosch <jvo@superbrave.nl>
 */
class IterableNormalizerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var IterableNormalizer
     */
    private $normalizer;

    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        $this->normalizer = new IterableNormalizer();
        $this->serializer = new Serializer([
            new DateTimeNormalizer(),
            $this->normalizer,
        ], [
            new JsonEncoder()
        ]);

        $this->normalizer->setNormalizer($this->serializer);
    }

    /**
     * Tests if @see IterableNormalizer::supportsNormalization() returns false
     * when the data is not iterable.
     *
     * @return void
     */
    public function testSupportsNormalizationReturnsFalseWhenDataIsNotIterable()
    {
        $this->assertFalse($this->normalizer->supportsNormalization('no object'));
        $this->assertFalse($this->normalizer->supportsNormalization(new \stdClass()));
    }

    /**
     * Tests if @see IterableNormalizer::supportsNormalization() returns true
     * when the data is iterable.
     *
     * @return void
     */
    public function testSupportsNormalizationReturnsTrueWhenDataIsIterable()
    {
        $this->assertTrue($this->normalizer->supportsNormalization(array()));
        $this->assertTrue($this->normalizer->supportsNormalization(new ArrayCollection()));
    }

    /**
     * Tests if @see IterableNormalizer::$normalizer returns the expected array of a iterable object.
     *
     * @return void
     *
     * @throws \Exception
     */
    public function testNormalize()
    {
        $collection = new ArrayCollection();
        $collection->add(new \DateTime('2020/01/01'));
        $collection->add(new \DateTime('2020/01/01'));

        $this->assertEquals(
            [
                '2020-01-01T00:00:00+00:00',
                '2020-01-01T00:00:00+00:00',
            ],
            $this->normalizer->normalize($collection)
        );
    }

    /**
     * Test is @see IterableNormalizer::$normalizer returns the expected json normalized data
     * for serialization through the Serializer.
     *
     * @return void
     *
     * @throws \Exception
     */
    public function testNormalizeThroughJsonSerializer()
    {
        $data = [
            'foo' => 'bar',
            'baz' => 1,
            'qux' => [
                new ArrayCollection([
                    new \DateTime('2020/01/01'),
                    new \DateTime('2020/01/01'),
                ])
            ]
        ];

        $this->assertStringEqualsFile(
            __DIR__ . '/../../Resources/json/iterable_normalize_result.json',
            $this->serializer->serialize($data, 'json')
        );
    }
}

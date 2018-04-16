<?php
/**
 * This file contains the Export annotation class
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

namespace SuperBrave\GdprBundle\Annotations;

/**
 * Annotation to flag an entity field to be exported
 * to conform with the GDPR right of portability.
 *
 * @category Bundle
 * @package  Gdpr
 * @author   Superbrave <info@superbrave.nl>
 * @license  https://github.com/superbrave/gdpr-bundle/blob/master/LICENSE MIT
 * @link     https://www.superbrave.nl/
 *
 * @Annotation()
 * @Annotation\Target({"PROPERTY"})
 */
class Export
{
    /**
     * Use given name instead of column name in the exported data.
     *
     * @var string
     */
    public $fieldName;
}

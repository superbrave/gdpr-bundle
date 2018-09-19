# Exporter

Provides an annotation for exporting object data to a specific format

## Exporter encoder types

- xml
- json

## Register new normalizers

You can create your own normalizers and add them to the compiler pass by tagging the service.

```yml
your_bundle_name.your_normalizer:
    class: Your\Class\To\Your\Normalizer
    tags:
        - { name: superbrave_gdpr.serializer.normalizer }
```

## Register new encoders

You can create your own encoders and register them to the compiler pass by tagging the service.

```yml
your_bundle_name.your_encoder:
    class: Your\Class\To\Your\Encoder
    tags:
        - { name: superbrave_gdpr.serializer.encoder }
```

## Usage

You can export objects by using the exporter service:

```php
<?php

$this->get('superbrave_gdpr.exporter')->exportObject($object, $objectName, $format);
```

## Examples

#### Annotations

Setting annotations on the object. Only the marked properties are being exported. If you mark an object, you also need to put annotations on properties of the object.

```php
<?php

use Superbrave\GdprBundle\Annotation as GDPR;

class Order
{
    /**
     * @var int
     *
     * @GDPR\Export()
     */
    private $id;

    /**
     * @var Product
     *
     * @GDPR\Export()
     */
    private $product;
}

class Product
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     *
     * @GDPR\Export()
     */
    private $name;
}
```

# Exporter

Provides an annotation for exporting object data to a specific format

## Exporter encoder types

- xml
- json

## Register new normalizers

You can create your own normalizers and register them in the compiler pass by tagging the service.

```yml
your_bundle_name.your_normalizer:
    class: Your\Class\To\Your\Normalizer
    tags:
        - { name: superbrave_gdpr.serializer.normalizer }
```

## Register new encoders

You can create your own encoders and register them in the compiler pass by tagging the service.

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

$this->get('superbrave_gdpr.exporter')->exportObject($object, $objectName, $encoding);
```

## Examples

#### Annotations

Setting annotations on the object. Only the marked properties are being exported.

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
    protected $id;

    /**
     * @var Product
     *
     * @GDPR\Export()
     */
    protected $product;
}
```

You can even mark object properties but don't forget to put annotations on the object itself.

```php
<?php

use Superbrave\GdprBundle\Annotation as GDPR;

class Product
{
    /**
     * @var int
     *
     * @GDPR\Export()
     */
    protected $id;

    /**
     * @var int
     *
     * @GDPR\Export()
     */
    protected $name;
}
```

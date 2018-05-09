# Anonymizer

Provides an annotation for anonymizing objects based on specific anonymization types. Specific anonymization types can be created and registered with the compiler pass.

## Anonymization types

- fixed
- dateTime
- ip
- object
- collection
- null

## Registering new anonymizers

You can create your own anonymizers and register them in the compiler pass by tagging the service:
The `type` parameter specifies the name to be used in the `type` parameter of the annotation.

```yml
your_bundle_name.your_anonymizer:
    class: Your\Class\To\Your\Anonymizer
    tags:
        - { name: superbrave_gdpr.anonymizer, type: your_type }
```

The example above will register an anonymizer with the name `your_type`. You can then use it as followed:
```@GDPR\Anonymize(type="your_type")```

## Usage

You can anonymize objects by using the anonymizer service:

```php
<?php

$this->get('superbrave_gdpr.anonymizer')->anonymize($object);
```

## Examples

#### Type: fixed

Anonymizes the property with a fixed value.

##### Simple fixed value 

Setting a fixed value for the property.

```php
<?php

use SuperBrave\GdprBundle\Annotation as GDPR;

 /**
  * @var string
  *
  * @GDPR\Anonymize(type="fixed", value="anonymized")
  */
 protected $value;
```

##### Advanced fixed value

Curly braces can be used here to concat the fixed value with the value of a property on the object.  
The value between the quotes has to be a property.  

First a check is performed to determine if the property is public, if so that is used.  
The second check is to determine whether or not the property has a getter, if so that is used.  
If the checks above both failed it resorts back to a ReflectionProperty

```php
<?php

use SuperBrave\GdprBundle\Annotation as GDPR;

 /**
  * @var string
  *
  * @GDPR\Anonymize(type="fixed", value="firstName-{id}")
  */
 protected $firstName;
```

#### Type: dateTime

Anonymizes the date time field by setting the month and day to 01 and hours, minutes and seconds to 00.

```php
<?php

use SuperBrave\GdprBundle\Annotation as GDPR;

 /**
  * @var \DateTimeInterface|string|int
  *
  * @GDPR\Anonymize(type="dateTime")
  */
 protected $createdAt;
```

#### Type: ip

Anonymizes the ip address by zeroing the last bytes of an ipv4 or ipv6 address.

```php
<?php

use SuperBrave\GdprBundle\Annotation as GDPR;

 /**
  * @var string
  *
  * @GDPR\Anonymize(type="ip")
  */
 protected $ipAddress;
```

#### Type: null

Anonymizes the property by setting it to null.

```php
<?php

use SuperBrave\GdprBundle\Annotation as GDPR;

 /**
  * @var mixed
  *
  * @GDPR\Anonymize(type="null")
  */
 protected $city;
```

#### Type: object

The object type anonymizer is to indicate that the property is an actual object which itself can have annotations.

```php
<?php

use SuperBrave\GdprBundle\Annotation AS GDPR;

class Order
{
    /**
     * @var string;
     *
     * @GDPR\Anonymize(type="ip")
     */
    protected $ipAddress;
}
```

```php
<?php

use SuperBrave\GdprBundle\Annotation as GDPR;

 /**
  * @var Order
  *
  * @GDPR\Anonymize(type="object")
  */
 protected $order;
```

#### Type: collection

The collection type anonymizer is to indicate that the property is an collection of objects which itself can be anonymized.

```php
<?php

use SuperBrave\GdprBundle\Annotation AS GDPR;

class Order
{
    /**
     * @var string;
     *
     * @GDPR\Anonymize(type="ip")
     */
    protected $ipAddress;
}
```

```php
<?php

use SuperBrave\GdprBundle\Annotation as GDPR;

 /**
  * @var Order[]
  *
  * @GDPR\Anonymize(type="collection")
  */
 protected $orders;
```

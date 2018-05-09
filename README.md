# gdpr-bundle
A Symfony bundle for using entity annotations according to GDPR requirements and anonymizing/exporting data

[![Build Status](https://travis-ci.org/superbrave/gdpr-bundle.svg?branch=master)](https://travis-ci.org/superbrave/gdpr-bundle)
[![Total Downloads](https://poser.pugx.org/superbrave/gdpr-bundle/downloads.svg)](https://packagist.org/packages/superbrave/gdpr-bundle)
[![Latest Stable Version](https://poser.pugx.org/superbrave/gdpr-bundle/v/stable.svg)](https://packagist.org/packages/superbrave/gdpr-bundle)

## Overview

- Provides an annotation for anonymizing objects based on specific anonymization types.
You can create your own anonynimization types, you can read more on how in: [Registering new anonymizers](Resources/doc/anonymizer.md#registering-new-anonymizers)

- Provides an annotation for exporting object data to a specific format. 

## Setup

### Installation

Using this package is similar to all Symfony bundles.

#### Step 1.

Open a command console, enter your project directory and execute the
following command to download the latest version of this bundle:

```
$ composer require superbrave/gdpr-bundle
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

#### Step 2.

Then, enable the bundle by adding it to the list of registered bundles
in the `app/AppKernel.php` file of your project:

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...
            new Superbrave\GdprBundle\SuperbraveGdprBundle(),
        );
        // ...
    }
    // ...
}
```

## Documentation

The source of the documentation is stored in the `Resources/doc/` folder:

[Documentation for Anonymizer](Resources/doc/anonymizer.md)

[Documentation for Exporter](Resources/doc/exporter.md)

## License

This bundle is under the MIT license. See the complete license [in the bundle](LICENSE)

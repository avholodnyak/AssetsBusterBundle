GulpBusterBundle
==========================

[![Build Status](https://travis-ci.org/avholodnyak/AssetsBusterBundle.svg?branch=master)](https://travis-ci.org/avholodnyak/AssetsBusterBundle)
[![Coverage Status](https://coveralls.io/repos/github/avholodnyak/AssetsBusterBundle/badge.svg?branch=master)](https://coveralls.io/github/avholodnyak/AssetsBusterBundle?branch=master)

When you write your front-end application you often need to update
cached assets on the client side. This bundle provides you an ability
to update cached assets by using busters files (you can generate these files by using
[gulp-buster](https://www.npmjs.com/package/gulp-buster) package).

This bundle uses the
[custom version strategies feature](http://symfony.com/doc/current/frontend/custom_version_strategy.html)
that was introduced in Symfony 3.1.
When I was creating this bundle I was inspired by
[that article](http://symfony.com/doc/current/frontend/custom_version_strategy.html).

## Installation

Install the bundle using composer:

```
$ composer require avholodnyak/assets-buster-bundle
```

Then, enable the bundle by adding it to the list of registered bundles
in the `app/AppKernel.php` file of your project:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new AVHolodnyak\AssetsBusterBundle\AssetsBusterBundle(),
    );
}
```

Then, configure the bundle in your `app/config/config.yml` file:

``` yml
assets_buster:
    # Absolute path to the busters file
    busters_path: "%kernel.root_dir%/../web/busters.json"

    # (Optional) Version format (sprintf-compatible format string). Default value is "%%s?%%s"
    version_format: "%%s?v=%%s"
```

## How to use

Just use the standard symfony `asset()` function for Twig templates
(or `$view["assets"]->getUrl()` for PHP templates),
and a version of an asset file will be appended automatically.
If you don't want to append a version of an asset file,
then don't put the corresponding version to the busters file.

Here is an example of how to use the bundle with Twig templates:

``` html
<link rel="stylesheet" href="{{ asset('js/example-script.js') }}">
```

By default the bundle uses `"%s?%s"` version format. If a corresponding version will be found in the
busters file, then it will be appended to the result asset path:

``` html
<link rel="stylesheet" href="/js/example-script.js?cc1d8837ebc45c34f9e35217db1d2a7e">
```

If no version will be found in the busters file, then nothing will be appended to the result path:

``` html
<link rel="stylesheet" href="/js/example-script.js">
```

You can change the way the bundle appends a version of an asset file
by changing the `version_format` option in your configuration file (see the Installation section above).

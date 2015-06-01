# CacheTagsBundle

CacheTagsBundle is simple Cache Tag and Cache Invalidation for Varnish Cache

[![Total Downloads](https://poser.pugx.org/lbarulski/cache-tags-bundle/downloads.png)](https://packagist.org/packages/lbarulski/cache-tags-bundle)

# varnish 3
```
sub vcl_recv {
    if (req.request == "BAN") {
        if (req.http.X-CACHE-TAG) {
            ban("obj.http.X-CACHE-TAGS ~ " + req.http.X-CACHE-TAG);
        } else {
            error 400 "Tag not given";
        }

        error 200 "Banned";
    }
}
```

## Requirements

* PHP 5.3+
* Symfony2

Installation
------------

The CacheTagsBundle library is available on [Packagist] (https://packagist.org/packages/lbarulski/cache-tags-bundle). You can install it using [Composer] (http://getcomposer.org):

#### Method 1

Simply run assuming you have installed composer.phar or composer binary:

```bash
$ composer require lbarulski/cache-tags-bundle
```

#### Method 2

1. Add the following lines in your composer.json:

```js
{
  "require": {
    "lbarulski/cache-tags-bundle": "dev-master"
  }
}
```

2. Run the composer to download the bundle

```bash
$ composer update lbarulski/cache-tags-bundle
```

### Add this bundle to your application's kernel

```php
// app/ApplicationKernel.php
public function registerBundles()
{
    return array(
        // ...
        new lbarulski\CacheTagsBundle\CacheTagsBundle(),
        // ...
    );
}
```

### Configuration config.yml example

```yaml
// app/config/config.yml
cache_tags:
    response:
        tag: X-CACHE-TAGS
    proxies:
        varnish:
            - { host: host.tld, port: 80, path: /, timeout: 1, header: X-CACHE-TAG }
```

### Usage examples:

#### Controller: plain cache tag

```php
// Acme\MainBundle\Controller\ArticleController.php

use lbarulski\CacheTagsBundle\Annotation\CacheTag\Plain;
...

/**
 * @CacheTag\Plain("article_name")
 **/
public function articleAction(Request $request)
{
    ...
    
    $response = new Response('...');
    $response->setPublic();
    $response->setTtl(3600);
    
    return $response;
}
```

#### Controller: request attribute tag

```php
// Acme\MainBundle\Entity\Article.php

use lbarulski\CacheTagsBundle\Tag\TagInterface;

class Article implements TagInterface
{
    ...
    
    public function getTag()
	{
		return 'article_'.$this->getId();
	}
}
```

```php
// Acme\MainBundle\Controller\ArticleController.php

use lbarulski\CacheTagsBundle\Annotation\CacheTag\RequestAttribute;
...

/**
 * @ParamConverter("article")
 * @CacheTag\RequestAttribute("article")
 **/
public function articleAction(Article $article)
{
    ...
    
    $response = new Response('...');
    $response->setPublic();
    $response->setTtl(3600);
    
    return $response;
}
```

#### View Template: use esi controller

```twig
{{ render_esi(controller('MainBundle:Article:article', { article: article.id })}}
```

### Invalidate

#### Command invalidate TAG:

```bash
$ ./app/console cache_tags:invalidate tag
```

#### Invalidate TAG:

```php
// Acme\MainBundle\Controller\ArticleController.php

use lbarulski\CacheTagsBundle\Tag\Plain;
...

public function updateArticleAction(Article $article)
{
    ...
    $tag = 'article_name';
    $this->get('cache_tags.invalidator')->invalidate(new Plain($tag));
    ...
}
```

License
-------

This library is released under the MIT license. See the included
[LICENSE](LICENSE) file for more information.

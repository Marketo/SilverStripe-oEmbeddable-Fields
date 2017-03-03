# SilverStripe-oEmbeddable-Fields

There are three steps to start using this module:

***First.*** Install via composer (or drop this entire folder in your docroot):

```
composer install marketo/silverstripe-oembeddable-fields
```

***Second.*** Enable the extension to the appropriate DataObject(s) or Page type via a `_config/*.yaml` file:

```
Page:
  extensions:
    oEmbeddableFields
```

***Last.*** Add one or more of the available fields:

```
Page:
  extensions:
    oEmbeddableFields
  enabled_oembed_fields:
    - Slideshare
    - Mixcloud
    - YouTube
    - Vidyard
    - BrainsharkID
```
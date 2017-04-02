# SilverStripe oEmbeddable Fields

## What is this?

This module allows you to quickly collect Slideshare, Mixcloud, YouTube, Vidyard, and Brainshark URLs on any DataObject or Page type.  Upon "set"ting any of these fields, the extension will immediately validate the URL and strip the field down to its unique identifier, storing only the identifier to the database.  Getter methods then allow the template to build the appropriate embed codes.

## Getting started

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
    - Wistia
    - Vimeo
    - Image
```
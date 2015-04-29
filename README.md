pixSortableBehaviorBundle
=========================

Offers a sortable feature for your Symfony2 admin listing

Implementation for the Sonata Admin bundle explained in the cookbook

https://github.com/sonata-project/SonataAdminBundle/blob/master/Resources/doc/cookbook/recipe_sortable_listing.rst

### Configuration

By default, this extension works with Doctrine ORM, but you can choose to use Doctrine MongoDB by defining the driver configuration : 

``` yaml
# app/config/config.yml
pix_sortable_behavior:
    db_driver: mongodb # default value : orm
    position_property:
        default: sort #default value : position
        entities:
            AcmeBundle/Entity/Foobar: order
            AcmeBundle/Entity/Baz: rang
```

## Changes in my fork

1. Added function ``sortableObjectLastPosition`` in Twig extension. This caused removing $lastPosition and $positionService from ``ClientAdmin`` configuration. Also changed templates:
``` twig
{% set lastPosition = sortableObjectLastPosition(object) %}
...
```

2. Added PropertyAccessor in services and Twig extension for getting and setting values in object.

3. Refactored controller and services.

4. Added two separate templates: for Bootstrap v2 & v3.

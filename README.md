pixSortableBehaviorBundle
=========================

Offers a sortable feature for your Symfony2 admin listing

Implementation for the Sonata Admin bundle explained in the cookbook

https://github.com/sonata-project/SonataAdminBundle/blob/master/Resources/doc/cookbook/recipe_sortable_listing.rst



What's new in my fork
---------------------

* Added some code in PositionHandler from fork https://github.com/Stelss0007/pixSortableBehaviorBundle

* Added some code in SortableAdminController from fork https://github.com/dlabs/pixSortableBehaviorBundle

* Added two different templates for bootstrap v2 & v3. Now use another paths for templates: `PixSortableBehaviorBundle:Sort/bootstrap2:_sort.html.twig` or `PixSortableBehaviorBundle:Sort/bootstrap3:_sort.html.twig`

* Replaced ugly html entity arrows by bootstrap icons

* It's strongly recommended in route 'move' use requirement for value {move}: `$collection->add('move', $this->getRouterIdParameter() . '/move/{move}', [], ['move' => 'up|down|top|bottom']);`



TODO
----

* Add translation for titles in _sort.html.twig

* Add ability to use custom name of the order field.

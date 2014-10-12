pixSortableBehaviorBundle
=========================

Offers a sortable feature for your Symfony2 admin listing

Implementation for the Sonata Admin bundle explained in the cookbook

https://github.com/sonata-project/SonataAdminBundle/blob/master/Resources/doc/cookbook/recipe_sortable_listing.rst



What's new in that fork
=======================

1. Added some code in PositionHandler from fork https://github.com/Stelss0007/pixSortableBehaviorBundle

2. Added some code in SortableAdminController from fork https://github.com/dlabs/pixSortableBehaviorBundle

3. Added two different templates for bootstrap v.2 & v.3. Now use another paths for templates: `PixSortableBehaviorBundle:Sort/bootstrap2:_sort.html.twig` or `PixSortableBehaviorBundle:Sort/bootstrap3:_sort.html.twig`

4. Used bootstrap icons instead of ugly html entity arrows

5. It's strongly recommended in route 'move' use requirement for value {move}: `$collection->add('move', $this->getRouterIdParameter() . '/move/{move}', [], ['move' => 'up|down|top|bottom']);`



TODO
====

1. Add translation for titles in _sort.html.twig

2. Add ability to use custom name of the order field.

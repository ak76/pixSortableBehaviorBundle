<?php
/*
 * This file is part of the pixSortableBehaviorBundle.
 *
 * (c) Nicolas Ricci <nicolas.ricci@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pix\SortableBehaviorBundle;

use Doctrine\ORM\EntityManager;
use Gedmo\Sortable\SortableListener;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class PixSortableBehaviorBundle extends Bundle
{
    public function boot()
    {
        /**
         * @var $em EntityManager
         */
        $em = $this->container->get('doctrine.orm.default_entity_manager');

        // get the event manager
        $evm = $em->getEventManager();
        $evm->addEventSubscriber(new SortableListener());
    }
}

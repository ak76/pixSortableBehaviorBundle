<?php
/*
 * This file is part of the pixSortableBehaviorBundle.
 *
 * (c) Nicolas Ricci <nicolas.ricci@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pix\SortableBehaviorBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\RedirectResponse;

class SortableAdminController extends CRUDController
{
    /**
     * Move element
     *
     * @param $id
     * @param $move
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function moveAction($id, $move)
    {
        $id     = $this->get('request')->get($this->admin->getIdParameter());
        $page   = $this->get('request')->get('page');
        $filters= $this->admin->getFilterParameters();
        $filters['_page'] = $page;
        $object = $this->admin->getObject($id);

        $position_service = $this->get('pix_sortable_behavior.position');
        $last_position = $position_service->getLastPosition(get_class($object));
        $new_position = $position_service->getNewPosition($object, $move, $last_position);

        $object->setPosition($new_position);
        $this->admin->update($object);

        if ($this->isXmlHttpRequest()) {
            return $this->renderJson([
                'result' => 'ok',
                'objectId' => $this->admin->getNormalizedIdentifier($object)
            ]);
        }

        $translator = $this->get('translator');
        $this->get('session')->getFlashBag()->set('sonata_flash_info', $translator->trans('Position updated'));

        return new RedirectResponse($this->admin->generateUrl('list', ['filter' => $filters]));
    }
}

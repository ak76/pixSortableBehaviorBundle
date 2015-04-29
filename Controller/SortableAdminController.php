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
use Symfony\Component\HttpFoundation\Request;

class SortableAdminController extends CRUDController
{
    /**
     * Move element
     *
     * @param Request $request
     * @param         $id
     * @param         $move
     *
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function moveAction(Request $request, $id, $move)
    {
        $id = $request->get($this->admin->getIdParameter());
        $object = $this->admin->getObject($id);

        $this->get('pix_sortable_behavior.position')->updatePosition($object, $move);
        $this->admin->update($object);

        if ($this->isXmlHttpRequest()) {
            return $this->renderJson(array(
                'result' => 'ok',
                'objectId' => $this->admin->getNormalizedIdentifier($object)
            ));
        }

        $this->get('session')->getFlashBag()->set('sonata_flash_info', $this->get('translator.default')->trans('Position updated'));

        return new RedirectResponse($this->admin->generateUrl('list', array('filter' => $this->admin->getFilterParameters())));
    }
}

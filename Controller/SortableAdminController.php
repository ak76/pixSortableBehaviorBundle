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
        $translator = $this->get('translator');

        if (!$this->admin->isGranted('EDIT')) {
            $this->addFlash(
                'sonata_flash_error',
                $translator->trans('flash_error_no_rights_update_position')
            );

            return $this->getRedirectResponse();
        }

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

        $this->addFlash('sonata_flash_info', $translator->trans('flash_success_position_updated'));

        return $this->getRedirectResponse();
    }

    /**
     * @return RedirectResponse
     */
    private function getRedirectResponse()
    {
        return new RedirectResponse($this->admin->generateUrl('list', array('filter' => $this->admin->getFilterParameters())));
    }
}

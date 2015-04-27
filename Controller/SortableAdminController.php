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

use Pix\SortableBehaviorBundle\Services\PositionHandler;
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

        /** @var PositionHandler $position_service */
        $position_service = $this->get('pix_sortable_behavior.position');
        $entity = \Doctrine\Common\Util\ClassUtils::getClass($object);
        $last_position = $position_service->getLastPosition($entity);
        $position = $position_service->getPosition($object, $position, $last_position);
        $setter = sprintf('set%s', ucfirst($position_service->getPositionFieldByEntity($entity)));
        $object->{$setter}($position);
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

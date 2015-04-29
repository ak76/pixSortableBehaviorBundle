<?php
/*
 * This file is part of the pixSortableBehaviorBundle.
 *
 * (c) Nicolas Ricci <nicolas.ricci@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pix\SortableBehaviorBundle\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\Security\Core\Util\ClassUtils;

class PositionORMHandler extends PositionHandler
{
    /** @var EntityManager */
    protected $em;

    /**
     * @param PropertyAccessor $propertyAccessor
     * @param EntityManager    $entityManager
     */
    public function __construct($propertyAccessor, EntityManager $entityManager)
    {
        parent::__construct($propertyAccessor);
        $this->em = $entityManager;
    }

    /**
     * @param      $entity
     * @param bool $forceUpdate
     *
     * @return int
     */
    public function getLastPosition($entity, $forceUpdate = false)
    {
        if ($this->lastPosition && !$forceUpdate) {
            return $this->lastPosition;
        }

        $entity = is_object($entity) ? ClassUtils::getRealClass($entity) : $entity;
        $query = $this->em->createQuery(sprintf(
            'SELECT MAX(m.%s) FROM %s m',
            $this->getPositionPropertyByEntity($entity),
            $entity
        ));
        $result = $query->getResult();

        if (array_key_exists(0, $result)) {
            $this->lastPosition = intval($result[0][1]);

            return $this->lastPosition;
        }

        return 0;
    }
}

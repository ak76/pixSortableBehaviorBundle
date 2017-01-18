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

use Doctrine\Common\Util\ClassUtils;
use Doctrine\ORM\EntityManager;
use Symfony\Component\PropertyAccess\PropertyAccessor;

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

        $entityClass = is_object($entity) ? ClassUtils::getClass($entity) : $entity;
        $groups = $this->getSortableGroupsFieldByEntity($entityClass);

        $qb = $this->em->createQueryBuilder()
            ->select(sprintf('MAX(t.%s)', $this->getPositionPropertyByEntity($entityClass)))
            ->from($entityClass, 't')
        ;

        if ($groups) {
            foreach ($groups as $groupName) {
                $getter = 'get' . $groupName;

                if ($entity->$getter()) {
                    $qb
                        ->andWhere(sprintf('t.%s = :group_%s', $groupName, $groupName))
                        ->setParameter(sprintf('group_%s', $groupName), $entity->$getter())
                    ;
                }
            }
        }

        $this->lastPosition = (int)$qb->getQuery()->getSingleScalarResult();

        return $this->lastPosition;
    }
}

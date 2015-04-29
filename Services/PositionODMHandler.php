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

use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class PositionODMHandler extends PositionHandler
{
    /** @var DocumentManager */
    protected $dm;

    /**
     * @param PropertyAccessor $propertyAccessor
     * @param DocumentManager  $documentManager
     */
    public function __construct($propertyAccessor, DocumentManager $documentManager)
    {
        parent::__construct($propertyAccessor);
        $this->dm = $documentManager;
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

        $positionFiles = $this->getPositionPropertyByEntity($entity);
        $result = $this->dm
            ->createQueryBuilder($entity)
            ->hydrate(false)
            ->select($positionFiles)
            ->sort($positionFiles, 'desc')
            ->limit(1)
            ->getQuery()
            ->getSingleResult();

        if (is_array($result) && isset($result[$positionFiles])) {
            $this->lastPosition = $result[$positionFiles];

            return $this->lastPosition;
        }

        return 0;
    }
}

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

class PositionODMHandler extends PositionHandler
{
    /** @var DocumentManager */
    protected $dm;

    /**
     * @param \Symfony\Component\PropertyAccess\PropertyAccessor $propertyAccessor
     * @param DocumentManager $documentManager
     */
    public function __construct($propertyAccessor, DocumentManager $documentManager)
    {
        parent::__construct($propertyAccessor);
        $this->dm = $documentManager;
    }

    /**
     * @param $entity
     * @return int
     */
    public function getLastPosition($entity)
    {
        $positionFiles = $this->getPositionFieldByEntity($entity);
        $result = $this->dm
            ->createQueryBuilder($entity)
            ->hydrate(false)
            ->select($positionFiles)
            ->sort($positionFiles, 'desc')
            ->limit(1)
            ->getQuery()
            ->getSingleResult();

        if (is_array($result) && isset($result[$positionFiles])) {
            return $result[$positionFiles];
        }

        return 0;
    }
}

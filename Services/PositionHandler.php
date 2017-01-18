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
use Symfony\Component\PropertyAccess\PropertyAccessor;

abstract class PositionHandler
{
    const MOVE_UP = 'up';
    const MOVE_DOWN = 'down';
    const MOVE_TOP = 'top';
    const MOVE_BOTTOM = 'bottom';

    /** @var  PropertyAccessor */
    protected $propertyAccessor;

    /**
     * From config
     *
     * @var  array
     */
    protected $positionProperty;

    /**
     * From config
     *
     * @var array
     */
    protected $sortableGroups;

    /** @var  int */
    protected $lastPosition;

    /**
     * @param PropertyAccessor $propertyAccessor
     */
    public function __construct(PropertyAccessor $propertyAccessor)
    {
        $this->propertyAccessor = $propertyAccessor;
    }

    /**
     * @param $entity
     * @param bool $forceUpdate
     *
     * @return mixed
     */
    abstract public function getLastPosition($entity, $forceUpdate = false);

    /**
     * @return string
     */
    public static function getMoves()
    {
        return join('|', array(self::MOVE_UP, self::MOVE_DOWN, self::MOVE_TOP, self::MOVE_BOTTOM));
    }

    /**
     * @param array $positionProperty
     */
    public function setPositionProperty(array $positionProperty)
    {
        $this->positionProperty = $positionProperty;
    }

    /**
     * @param array $sortableGroups
     */
    public function setSortableGroups(array $sortableGroups)
    {
        $this->sortableGroups = $sortableGroups;
    }

    /**
     * @param $entity
     *
     * @return mixed
     */
    public function getPositionPropertyByEntity($entity)
    {
        if (is_object($entity)) {
            $entity = ClassUtils::getClass($entity);
        }

        if (isset($this->positionProperty['entities'][$entity])) {
            return $this->positionProperty['entities'][$entity];
        }
        else {
            return $this->positionProperty['default'];
        }
    }

    /**
     * @param $entity
     *
     * @return array
     */
    public function getSortableGroupsFieldByEntity($entity)
    {
        if (is_object($entity)) {
            $entity = ClassUtils::getClass($entity);
        }

        $groups = [];

        if (isset($this->sortableGroups['entities'][$entity])) {
            $groups = $this->sortableGroups['entities'][$entity];
        }

        return $groups;
    }

    /**
     * @param $object
     * @param $move
     */
    public function updatePosition($object, $move)
    {
        $propertyName = $this->getPositionPropertyByEntity($object);
        $lastPosition = $this->getLastPosition(ClassUtils::getClass($object), true);
        $currentPosition = $this->propertyAccessor->getValue($object, $propertyName);

        switch ($move) {
            case self::MOVE_UP:
                if ($currentPosition > 0) {
                    $currentPosition--;
                }
                break;

            case self::MOVE_DOWN:
                if ($currentPosition < $lastPosition) {
                    $currentPosition++;
                }
                break;

            case self::MOVE_TOP:
                if ($currentPosition > 0) {
                    $currentPosition = 0;
                }
                break;

            case self::MOVE_BOTTOM:
                if ($currentPosition < $lastPosition) {
                    $currentPosition = $lastPosition;
                }
                break;
        }

        $this->propertyAccessor->setValue($object, $propertyName, $currentPosition);
    }
}

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

use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\Security\Core\Util\ClassUtils;

abstract class PositionHandler
{
    const MOVE_UP = 'up';
    const MOVE_DOWN = 'down';
    const MOVE_TOP = 'top';
    const MOVE_BOTTOM = 'bottom';

    /** @var  PropertyAccessor */
    protected $propertyAccessor;

    /** @var  array */
    protected $positionProperty;

    public function __construct(PropertyAccessor $propertyAccessor)
    {
        $this->propertyAccessor = $propertyAccessor;
    }

    /**
     * @param $entity
     * @return mixed
     */
    abstract public function getLastPosition($entity);

    /**
     * @param $positionProperty
     */
    public function setPositionProperty($positionProperty)
    {
        $this->positionProperty = $positionProperty;
    }

    /**
     * @param $entity
     * @return mixed
     */
    public function getPositionPropertyByEntity($entity)
    {
        if (is_object($entity)) {
            $entity = ClassUtils::getRealClass($entity);
        }

        if (isset($this->positionProperty['entities'][$entity])) {
            return $this->positionProperty['entities'][$entity];
        } else {
            return $this->positionProperty['default'];
        }
    }

    /**
     * @param $object
     * @param $move
     */
    public function updatePosition($object, $move)
    {
        $propertyName = $this->getPositionPropertyByEntity($object);
        $lastPosition = $this->getLastPosition(ClassUtils::getRealClass($object));
        $currentPosition = $this->propertyAccessor->getValue($object, $propertyName);

        switch ($move) {
            case 'up' :
                if ($currentPosition > 0) {
                    $currentPosition--;
                }
                break;

            case 'down':
                if ($currentPosition < $lastPosition) {
                    $currentPosition++;
                }
                break;

            case 'top':
                if ($currentPosition > 0) {
                    $currentPosition = 0;
                }
                break;

            case 'bottom':
                if ($currentPosition < $lastPosition) {
                    $currentPosition = $lastPosition;
                }
                break;
        }

        $this->propertyAccessor->setValue($object, $propertyName, $currentPosition);
    }
}

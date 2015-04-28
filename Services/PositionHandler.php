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
    protected $positionField;

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
     * @param $positionField
     */
    public function setPositionField($positionField)
    {
        $this->positionField = $positionField;
    }

    /**
     * @param $entity
     * @return mixed
     */
    public function getPositionFieldByEntity($entity)
    {
        if (is_object($entity)) {
            $entity = ClassUtils::getRealClass($entity);
        }

        if (isset($this->positionField['entities'][$entity])) {
            return $this->positionField['entities'][$entity];
        } else {
            return $this->positionField['default'];
        }
    }

    /**
     * @param $object
     * @param $move
     */
    public function updatePosition($object, $move)
    {
        $fieldName = $this->getPositionFieldByEntity($object);
        $last_position = $this->getLastPosition(ClassUtils::getRealClass($object));
        $currentPosition = $this->propertyAccessor->getValue($object, $fieldName);

        switch ($move) {
            case 'up' :
                if ($currentPosition > 0) {
                    $currentPosition--;
                }
                break;

            case 'down':
                if ($currentPosition < $last_position) {
                    $currentPosition++;
                }
                break;

            case 'top':
                if ($currentPosition > 0) {
                    $currentPosition = 0;
                }
                break;

            case 'bottom':
                if ($currentPosition < $last_position) {
                    $currentPosition = $last_position;
                }
                break;
        }

        $this->propertyAccessor->setValue($object, $fieldName, $currentPosition);
    }
}

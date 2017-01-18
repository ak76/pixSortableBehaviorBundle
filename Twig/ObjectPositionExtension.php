<?php

namespace Pix\SortableBehaviorBundle\Twig;

use Pix\SortableBehaviorBundle\Services\PositionHandler;
use Symfony\Component\PropertyAccess\PropertyAccessor;

/**
 * Description of ObjectPositionExtension
 *
 * @author Volker von Hoesslin <volker.von.hoesslin@empora.com>
 */
class ObjectPositionExtension extends \Twig_Extension
{
    const NAME = 'objectPosition';

    /** @var PropertyAccessor $propertyAccessor */
    private $propertyAccessor;

    /** @var PositionHandler $positionService */
    private $positionService;

    /**
     * @param PropertyAccessor $propertyAccessor
     * @param PositionHandler  $positionService
     */
    function __construct(PropertyAccessor $propertyAccessor, PositionHandler $positionService)
    {
        $this->propertyAccessor = $propertyAccessor;
        $this->positionService = $positionService;
    }

    /**
     * @return PropertyAccessor
     */
    public function getPropertyAccessor()
    {
        return $this->propertyAccessor;
    }

    public function getPositionService()
    {
        return $this->positionService;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('sortableObjectPosition',
                function ($entity) {
                    return $this->getPropertyAccessor()->getValue($entity, $this->getPositionService()->getPositionPropertyByEntity($entity));
                }
            ),
            new \Twig_SimpleFunction('sortableObjectLastPosition',
                function ($entity) {
                    return $this->getPositionService()->getLastPosition($entity);
                }
            )
        );
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return self::NAME;
    }
}

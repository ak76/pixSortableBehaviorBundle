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
    const NAME = 'sortableObjectPosition';

    /** @var PositionHandler $positionService */
    private $positionService;

    function __construct(PropertyAccessor $propertyAccessor, PositionHandler $positionService)
    {
        $this->propertyAccessor = $propertyAccessor;
        $this->positionService = $positionService;
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

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction(self::NAME,
                function ($entity) {
                    return $this->propertyAccessor->getValue($entity, $this->positionService->getPositionFieldByEntity($entity));
                }
            )
        );
    }
}

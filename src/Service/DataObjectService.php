<?php

namespace Basilicom\DataQualityBundle\Service;

use Pimcore\Model\DataObject;

class DataObjectService
{

    public function __construct()
    {
    }

    public function getObjectDepth($dataObject, $depth = 0): int
    {
        if ($dataObject->getType() == "folder") {
            throw new \Exception("The object structure is broken. There is a variant attached to a folder instead of an object");
        }
        if ($dataObject->getType() == "object") {
            return $depth;
        }
        $depth++;
        return $this->getObjectDepth($dataObject->getParent(), $depth);
    }
}

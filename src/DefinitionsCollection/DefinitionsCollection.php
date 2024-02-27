<?php

namespace BigJabber\DataQualityBundle\DefinitionsCollection;

use BigJabber\DataQualityBundle\Definition\MinimumStringLengthDefinition;
use BigJabber\DataQualityBundle\Definition\NotEmptyDefinition;

class DefinitionsCollection
{
    const TYPES = [
        'Not Empty'             => NotEmptyDefinition::class,
        'Minimum String Length' => MinimumStringLengthDefinition::class,
    ];

    public static function getAllTypes(): array
    {
        return self::TYPES;
    }
}

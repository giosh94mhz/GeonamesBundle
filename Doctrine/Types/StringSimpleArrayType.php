<?php
namespace Giosh94mhz\GeonamesBundle\Doctrine\Types;

use Doctrine\DBAL\Types\SimpleArrayType;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class StringSimpleArrayType extends SimpleArrayType
{
    const STRING_SIMPLE_ARRAY = 'string_simple_array';

    /**
     * {@inheritdoc}
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getVarcharTypeDeclarationSQL($fieldDeclaration);
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultLength(AbstractPlatform $platform)
    {
        return $platform->getVarcharDefaultLength();
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return self::STRING_SIMPLE_ARRAY;
    }

}

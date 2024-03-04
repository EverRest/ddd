<?php
declare(strict_types=1);

namespace App\Infrastructure\Event\Trait;

trait HasRemoveEmptyValuesFromArray
{
    /**
     * @param array $array
     *
     * @return array
     */
    protected function removeEmptyValues(array $array): array
    {
        return array_filter($array, fn($attribute) => $attribute !== null && $attribute !== '');
    }
}

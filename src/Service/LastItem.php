<?php
/**
 * @description : Service for retrieving items from a collection/array
 * @Author : Quentin Thomasset
 */

namespace App\Service;

class LastItem
{
    /**
     * This function returns the last item's id of an object collection
     * @param array $array
     * @return int
     */
    public function getLastItemId(array $array)
    {
        return array_values(array_slice($array, -1))[0]->getId();
    }
}

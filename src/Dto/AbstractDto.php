<?php

namespace App\Dto;

abstract class AbstractDto
{
    /**
     * @return array
     */
    public function toArray(): array
    {
        $arr = [];
        foreach (get_object_vars($this) as $key => $var) {
            $arr[$key] = $var;
        }

        return $arr;
    }
}

<?php

namespace Phapi\Dto;

abstract class BaseDto
{
    /**
     * @param array $data
     * @return array
    */
    public static function generate(array $data) : array
    {
        if(empty($data)){
            return [];
        }
        else if(isset($data[0]) && is_array($data[0])){
            $dtos = [];
            foreach ($data as $object){
                $dtos[] = static::map($object);
            }

            return $dtos;
        }
        else{
            $dto = static::map($data);
            return $dto;
        }
    }
}
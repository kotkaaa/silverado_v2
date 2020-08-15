<?php

namespace common\classes\Optional;

/**
 * Trait OptionalActiveRecordTrait
 * @package common\classes\Optional
 */
trait OptionalActiveRecordTrait
{

    /**
     * @param $name
     * @return mixed|object
     */
    public function __get($name)
    {

        $entity = parent::__get($name);

        if ($entity instanceof Optional) {
            return $entity->getObject();
        }

        return $entity;
    }

    /**
     * @param $condition
     * @return Optional
     */
    public static function findOne($condition): Optional
    {

        $entity = parent::findOne($condition);

        if ($entity instanceof Optional) {
            return $entity;
        }

        return Optional::of($entity);
    }

}
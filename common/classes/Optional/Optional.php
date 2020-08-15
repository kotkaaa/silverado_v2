<?php

namespace common\classes\Optional;

use yii\base\BaseObject;
use yii\helpers\Inflector;

/**
 * Class Optional
 * @package Optional
 */
class Optional
{

    /** @var object */
    private static $object;

    /**
     * Optional constructor.
     * @param $object
     */
    public function __construct(BaseObject $object = null)
    {
        self::$object = $object;
    }

    /**
     * @param $name
     * @param $arguments
     */
    public function __call($name, $arguments)
    {
        self::$object->{$name}(...$arguments);
    }

    /**
     * @param $name
     * @param $arguments
     */
    public static function __callStatic($name, $arguments)
    {
        self::$object::{$name}(...$arguments);
    }

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        if (method_exists(self::$object, Inflector::variablize('get' . $name))) {
            return self::$object->{Inflector::variablize('get' . $name)}();
        }

        return self::$object->{$name};
    }

    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        self::$object->{$name} = $value;
    }


    /**
     * @param $object
     * @return Optional
     */
    public static function of($object): Optional
    {
        return new Optional($object);
    }

    /**
     * @return object
     */
    public function getObject()
    {
        return self::$object;
    }

    /**
     * @param \Closure $closure
     * @return mixed
     */
    public function orElse(\Closure $closure)
    {
        if (!self::$object) {
            return $closure();
        }

        return self::$object;
    }

    /**
     * @return object|null
     */
    public function orNull()
    {
        if (!self::$object) {
            return null;
        }

        return self::$object;
    }

    /**
     * @param \Exception $exception
     * @return object
     * @throws \Exception
     */
    public function orThrow(\Exception $exception)
    {
        if (!self::$object) {
            throw $exception;
        }

        return self::$object;
    }
}
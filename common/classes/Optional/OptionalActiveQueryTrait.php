<?php

namespace common\classes\Optional;

/**
 * Trait OptionalActiveQueryTrait
 * @package common\classes\Optional
 */
trait OptionalActiveQueryTrait
{

    /**
     * @param null $db
     * @return Optional
     */
    public function one($db = NULL): Optional
    {
        return  Optional::of(parent::one($db));
    }
}
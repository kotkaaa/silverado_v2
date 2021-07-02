<?php

namespace common\modules\File\storages\interfaces;

use common\modules\File\behaviours\FileBehavior;

/**
 * Interface StorageInterface
 * @package common\modules\File\storages\interfaces
 */
interface StorageInterface
{

    /**
     * AbstractStorage constructor.
     * @param FileBehavior $behavior
     * @param array $config
     */
    public function __construct(FileBehavior $behavior, $config = []);

    /**
     * @param array $files
     * @return mixed
     */
    public function process(array $files);
}
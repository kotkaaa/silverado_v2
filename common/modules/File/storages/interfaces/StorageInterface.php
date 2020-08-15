<?php

namespace common\modules\File\storages\interfaces;

use common\modules\File\behaviours\FileBehaviour;

/**
 * Interface StorageInterface
 * @package common\modules\File\storages\interfaces
 */
interface StorageInterface
{

    /**
     * AbstractStorage constructor.
     * @param FileBehaviour $behavior
     * @param array $config
     */
    public function __construct(FileBehaviour $behavior, $config = []);

    /**
     * @param array $files
     * @return mixed
     */
    public function process(array $files);
}
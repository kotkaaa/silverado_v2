<?php

namespace common\modules\File\storages;

use common\modules\File\behaviours\FileBehaviour;

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
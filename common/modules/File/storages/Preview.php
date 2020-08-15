<?php


namespace common\modules\File\storages;

/**
 * Class Preview
 * @package common\modules\File\storages
 */
class Preview
{

    public const ALGORITHM_RESIZE = 'resize';
    public const ALGORITHM_FIT = 'fit';

    /** @var string */
    public $name;

    /** @var int */
    public $width;

    /** @var int */
    public $height;

    /** @var string */
    public $algorithm;

    /**
     * Preview constructor.
     * @param string $name
     * @param int $width
     * @param int $height
     * @param string $algorithm
     */
    public function __construct(string $name, int $width, int $height, $algorithm = self::ALGORITHM_FIT)
    {
        $this->name = $name;
        $this->width = $width;
        $this->height = $height;
        $this->algorithm = $algorithm;
    }

}
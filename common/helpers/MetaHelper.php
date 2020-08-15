<?php


namespace common\helpers;

/**
 * Class MetaHelper
 * @package common\helpers
 */
class MetaHelper
{
    /** @var string */
    public const ROBOTS_INDEX_FOLLOW = 'index,follow';
    public const ROBOTS_NOINDEX_FOLLOW = 'noindex,follow';
    public const ROBOTS_NOINDEX_NOFOLLOW = 'noindex,nofollow';

    /**
     * @param bool $combine
     * @return array
     */
    public static function metaRobots($combine = false): array
    {
        $robots = [
            self::ROBOTS_INDEX_FOLLOW,
            self::ROBOTS_NOINDEX_FOLLOW,
            self::ROBOTS_NOINDEX_NOFOLLOW
        ];

        if ($combine) {
            return array_combine($robots, $robots);
        }

        return $robots;
    }
}
<?php

namespace frontend\helpers;

/**
 * Class PhoneHelper
 * @package frontend\helpers
 */
class PhoneHelper extends \yii\helpers\Html
{
    /** @var string */
    public const PHONE_FORMAT_FULL = 'full';

    /** @var string */
    public const PHONE_FORMAT_SHORT = 'short';

    /** @var string */
    public const PHONE_FORMAT_FULL_PRINT = 'full_phint';

    /** @var string */
    public const PHONE_FORMAT_SHORT_PRINT = 'short_phint';

    /**
     * @param string $phoneString
     * @param string $format
     * @return string
     */
    public static function format(string $phoneString, string $format): string
    {
        $phoneString = preg_replace('/[\s\t\n\r\(\)\+\-]/', '', $phoneString);

        switch ($format) {
            // Full international phone format without whitespaces
            case self::PHONE_FORMAT_FULL:
                $phoneString = str_pad($phoneString, 13, '+38', STR_PAD_LEFT);
                break;

            // Short phone format without whitespaces
            case self::PHONE_FORMAT_SHORT:
                $phoneString = preg_replace('/^(3?8?)/', '', $phoneString);
                break;

            // Full international phone format with whitespaces
            case self::PHONE_FORMAT_FULL_PRINT:
                $phoneString = str_pad($phoneString, 13, '+38', STR_PAD_LEFT);
                $pad = [];

                foreach (str_split($phoneString) as $i => $char) {
                    if (in_array($i, [3, 6, 9, 11])) {
                        array_push($pad, ' ');
                    }
                    array_push($pad, $char);
                }

                $phoneString = implode('', $pad);
                break;

            // Short phone format with whitespaces
            case self::PHONE_FORMAT_SHORT_PRINT:
                $phoneString = preg_replace('/^(3?8?)/', '', $phoneString);
                $pad = [];

                foreach (str_split($phoneString) as $i => $char) {
                    if (in_array($i, [3, 6, 8])) {
                        array_push($pad, ' ');
                    }
                    array_push($pad, $char);
                }

                $phoneString = implode('', $pad);
                break;
        }

        return $phoneString;
    }
}
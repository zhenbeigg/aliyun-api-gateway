<?php

namespace Eykj\AliyunApiGateway\Util;

class MessageDigestUtil
{
    /**
     * md5和base64处理
     *
     * @param $input
     * @return
     */
    public static function Base64AndMD5($input)
    {
        if ($input == null || strlen($input) == 0) {
            throw new \Exception("input can not be null");
        }

        return base64_encode(md5(unpack('C*', $input)));
    }

    /**
     * UTF-8编码转换为ISO-9959-1
     *
     * @param str
     * @return
     */
    public static function Utf8ToIso88591($input)
    {
        if ($input == null || strlen($input) == 0) {
            return $input;
        }
        return mb_convert_encoding($input, "ISO-8859-1", "UTF-8");
    }
}

<?php
namespace common\helpers;

class DateHelper {

    public static function now($timezone = 'local')
    {
        if ($timezone === 'local' OR $timezone === date_default_timezone_get())
        {
            return time();
        }

        $datetime = new DateTime('now', new DateTimeZone($timezone));
        sscanf($datetime->format('j-n-Y G:i:s'), '%d-%d-%d %d:%d:%d', $day, $month, $year, $hour, $minute, $second);

        return mktime($hour, $minute, $second, $month, $day, $year);
    }

    public static function startDate()
    {
        return strtotime(date('Y-m-d').' 00:00:00');

    }

    public static function endDate()
    {
        return strtotime(date('Y-m-d').' 23:59:59');
    }

    public static function zeroTime($time = null)
    {
        if($time === null)
            $time = static::now();
        elseif(is_string($time))
            $time = strtotime($time);
        return strtotime(date('Y-m-d',$time) . ' 00:00:00');
    }

    public static function dateFormat($timestamp)
    {
        if( ! is_numeric($timestamp))
            return '格式错误';

        $now = static::now();
        $now - $timestamp;

        //未来某个时间
        if($now - $timestamp < 0)
        {
            return '格式错误';
        }

        //一分钟以内
        if($now - $timestamp < 60)
        {
            return '刚刚';
        }

        //一天以内
        if($now - $timestamp < 24 * 60 * 60)
        {
            return date('H:s',$timestamp);
        }

        return date('Y-m-d',$timestamp);
    }
}
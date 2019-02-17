<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 07.05.2018
 * Time: 11:21
 */


/**
 * Class Base_Analytics
 */
class Base_Analytics
{
    private static $_channels = array();

    public static function getChannels()
    {
        return self::$_channels;
    }

    public static function addChannel($channel, $value)
    {
        if(isset(self::$_channels[$channel])){
            throw new Exception('Stats channel already exists, channel ('.$channel.')');
        }

        self::$_channels[$channel] = $value;
    }

    public static function getChannel($channel)
    {
        return isset(self::$_channels[$channel]) ? self::$_channels[$channel] : null;
    }

    public static function getDefaultChannel()
    {
        $channel = array_keys(self::$_channels)[0];

        return self::getChannel($channel);
    }

    public static function isChannelExists($channel)
    {
        return isset(self::$_channels[$channel]);
    }



}
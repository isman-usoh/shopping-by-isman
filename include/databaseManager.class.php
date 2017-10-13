<?php
session_start();
include_once("config.class.php");
class DatabaseManager{
    static $link = null;
    static function openConnect(){
//        if(!is_null(self::$link)){
//            self::closeConnect();
//        }
        self::$link = @mysql_connect(Config::dbHost,Config::dbUsername,Config::dbPassword);
        mysql_select_db(Config::dbName,self::$link);
        mysql_query("SET NAMES UTF8");
    }
    static function closeConnect(){
        if(!is_null(self::$link)){
            mysql_close(self::$link);
        }
    }
}
<?php
/*
    * The MIT License (MIT)
    * 
    * Copyright (c) 2013 Developed by reg <entry.reg@gmail.com>
    * 
    * Permission is hereby granted, free of charge, to any person obtaining a copy
    * of this software and associated documentation files (the "Software"), to deal
    * in the Software without restriction, including without limitation the rights
    * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
    * copies of the Software, and to permit persons to whom the Software is
    * furnished to do so, subject to the following conditions:
    * 
    * The above copyright notice and this permission notice shall be included in
    * all copies or substantial portions of the Software.
    * 
    * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
    * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
    * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
    * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
    * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
    * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
    * THE SOFTWARE.
 */

namespace reg\plugin\mos3\smsb;
defined('MODROOT') or die('break');

final class System
{
    private static $_config = array();
    private function __construct(){}
    
    public static function init(array $config)
    {
        self::$_config = $config;
    }
    
    public static function getSettings()
    {
        if(count(self::$_config) < 1){
            throw new \Exception('settings not defined');
        }
        return self::$_config;
    }
    
    /**
     * Full reading template
     * @param string $name - name of template
     * @return string
     */
    public static function openView($name)
    {
        return file_get_contents(self::$_config['view']['path'] . '/' . $name . '.xml');
    }
    
    /**
     * Requested RSS pages
     * @return mixed
     * @throws \Exception
     */
    public static function getLevel()
    {
        try{
            return self::getParam('lev');
        }
        catch(\Exception $e){
            if(!isset(self::$_config['levels']['main'])){
                throw new \Exception('settings is not correct');
            }
            return self::$_config['levels']['main'];
        }
    }
    
    public static function getParam($ident)
    {
        if(!isset($_REQUEST[$ident])){
            throw new \Exception("Undefined param '" . $ident . "'");
        }
        return stripcslashes(urldecode($_REQUEST[$ident]));
    }
    
    public static function sharedGet($ident)
    {
        self::_sessionInit();
        if(!isset($_SESSION[$ident])){
            throw new \Exception('not found object ' . $ident);
        }
        return $_SESSION[$ident];
    }
    
    public static function sharedSet($value, $ident)
    {
        self::_sessionInit();
        $_SESSION[$ident] = $value;
    }
    
    public static function sharedFlush()
    {
        $id = session_id();
        if(!empty($id) && !session_destroy()){
//            throw new \Exception('');
        }
    }
    
    protected static function _sessionInit()
    {
        $id = session_id();
        if(empty($id)){
            session_id('SMSbShareVariableSID'); //require on stb
            if(!session_start()){
                throw new \Exception('initialization session failed');
            }
        }
        return $id;
    }
}
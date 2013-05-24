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

class Smsb
{
    /**
     * main settings
     * @var array
     */
    private $_config = null;
    
    public function __construct()
    {
        $this->_config = System::getSettings();
    }
    
    /**
     * viewing page
     * @param mixed $level
     * @return string
     * @throws \Exception
     */
    public function output($level)
    {
        switch($level){
            case $this->_config['levels']['main']:{
                return $this->_levelMain();
            }
            case $this->_config['levels']['captcha']:{ 
                return $this->_levelCaptcha(System::getParam('phone'), System::getParam('msg'));
            }
            case $this->_config['levels']['status']:{
                return $this->_levelStatus(System::getParam('vrfy'));
            }
        }
        throw new \Exception('not supported level');
    }
    
    protected function _levelMain()
    {
        $view = System::openView('main');

        $key            = new Keyboard();
        $keysRus        = $key->prepareKeyboard('rus', Keyboard::CASE_LOWER);
        $keysEng        = $key->prepareKeyboard('eng', Keyboard::CASE_LOWER);
        $keysUpperRus   = $key->prepareKeyboard('rus', Keyboard::CASE_UPPER);
        $keysUpperEng   = $key->prepareKeyboard('eng', Keyboard::CASE_UPPER);

        return str_replace(array(
                            '%localImage%',
                            '%keysRus%',
                            '%keysEng%',
                            '%keysUpperRus%',
                            '%keysUpperEng%',
                        ),
                        array(
                            MODROOT . '/view/images',
                            $keysRus,
                            $keysEng,
                            $keysUpperRus,
                            $keysUpperEng,
                        ), $view);
    }
    
    protected function _levelCaptcha($phone, $msg)
    {
        $remote     = new Remote();
        $content    = $remote->request($this->_config['providers']['beeline']['resource']);
        $session    = $remote->getCookiesRaw();
        
        $remote->setCookiesRaw($session);               // for all request
        $gif =  $remote->request($this->_captchaUrl()); // getting captcha image
        
        $captcha = $this->_config['images']['captcha'] . '/smsb_captcha.gif';
        file_put_contents($captcha, $gif);
        
        try{
            if(!preg_match("#afcode.+?value\s*=\s*['\"]?\s*([^'\"> ]+)#i", $content, $afcode)){
                throw new \Exception();
            }
            unset($content);
            
            System::sharedFlush();
            System::sharedSet($session, 'session');
            System::sharedSet(array(
                'phone'     => trim($phone),
                'msg'       => trim($msg),
                'afcode'    => $afcode[1],
            ), 'send');
            unset($afcode);
        }
        catch(\Exception $e){
            return $this->_sentFailure();
        }
        
        $view   = System::openView('captcha');
        $key    = new Keyboard();
        return str_replace(array(
                            '%localImage%',
                            '%keysDigit%',
                            '%phone%',
                            '%message%',
                            '%captcha%',
                        ),
                        array(
                            MODROOT . '/view/images',
                            $key->prepareKeyboard('digit'),
                            $phone,
                            $msg,
                            $captcha,
                        ), $view);
    }
    
    protected function _levelStatus($code)
    {
        try{
            $send = System::sharedGet('send');

            if(strlen($send['phone']) < 3){
                throw new \Exception();
            }

            $remote = new Remote();
            $remote->setCookiesRaw(System::sharedGet('session'));
            $res = $remote->push(new RemoteParam(array(
                'send'          => '',
                'smstext'       => $send['msg'],
                'afcode'        => $send['afcode'],
                'smstoprefix'   => substr($send['phone'], 0, 3),
                'smsto'         => substr($send['phone'], 3),
                'dirtysmstext'  => $send['msg'],
                'confirm_key'   => '',
                'confirmcode'   => $code,
                'x'             => mt_rand(1, 82),
                'y'             => mt_rand(1, 18),
            )), $this->_config['providers']['beeline']['resource']);
            
            //verify status - TODO:
            if(preg_match("#btn.*?check#i", $res)){
                return $this->_sentSuccess();
            }
        }
        catch(\Exception $e){}
        return $this->_sentFailure();
    }
    
    protected function _captchaUrl()
    {
        return $this->_config['providers']['beeline']['captcha'] . '&r=' . (mt_rand() / mt_getrandmax());
    }
    
    protected function _sentSuccess()
    {
        return str_replace('%status%', '1', System::openView('status'));
    }
    
    protected function _sentFailure()
    {
        return str_replace('%status%', '0', System::openView('status'));
    }    
}
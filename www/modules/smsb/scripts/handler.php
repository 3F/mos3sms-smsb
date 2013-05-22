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
                return $this->_levelCaptcha();
            }
            case $this->_config['levels']['status']:{
                return $this->_levelStatus();
            }
        }
        throw new \Exception('not supported level');
    }
    
    protected function _levelMain()
    {
        $view = System::openView('main');

        $key            = new Keyboard();
        $keysRus        = $key->prepareKeyboard(Keyboard::CASE_LOWER, 'rus');
        $keysEng        = $key->prepareKeyboard(Keyboard::CASE_LOWER, 'eng');
        $keysUpperRus   = $key->prepareKeyboard(Keyboard::CASE_UPPER, 'rus');
        $keysUpperEng   = $key->prepareKeyboard(Keyboard::CASE_UPPER, 'eng');

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
    
    protected function _levelCaptcha(&$data)
    {
        //TODO:
    }
    
    protected function _levelStatus()
    {
        //TODO:
    }
}
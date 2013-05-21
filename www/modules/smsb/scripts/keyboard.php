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

class Keyboard
{
    /*enum Commands {*/
    const CMD_SEND      = "SND";
    const CMD_CLEAR     = "CLR";
    const CMD_CAPS      = "CPS";
    const CMD_SWITCH    = "CHG";
    /*}*/
    
    public function prepareKeyboard($type = 'rus')
    {
        return $this->_transformToMos($this->_keys($type));
    }
    
    /**
     * @param string $type
     * @return array
     */
    protected function _keys($type)
    {
        $keys = array(
            'rus' => array(
                array(
                    '1', '2', '3', '4', '5', '6', '7', '8', '9', '0', '!', '?', '#', '$', '{', '[', "'", '\\"', '+', Keyboard::CMD_SWITCH
                ),                
                array(
                    'й', 'ц', 'у', 'к', 'е', 'н', 'г', 'ш', 'щ', 'з', 'х', 'ъ', '№', '>', '}', ']', '&', '(', ',', Keyboard::CMD_CAPS
                ),
                array(
                    'ф', 'ы', 'в', 'а', 'п', 'р', 'о', 'л', 'д', 'ж', 'э', 'ё', '%', '<', '^', ';', '/', ')', '`', Keyboard::CMD_CLEAR
                ),
                array(
                    'я', 'ч', 'с', 'м', 'и', 'т', 'ь', 'б', 'ю', ' ', '.', '-', ':', '|', '~', '*', '=', '@', '_', Keyboard::CMD_SEND
                ),
            ),
            'eng' => array(
                array(
                    '1', '2', '3', '4', '5', '6', '7', '8', '9', '0', '!', '?', '#', '$', '{', '[', "'", '\\"', '+', Keyboard::CMD_SWITCH
                ),                
                array(
                    'q', 'w', 'e', 'r', 't', 'y', 'u', 'i', 'o', 'p', ' ', ' ', '№', '>', '}', ']', '&', '(', ',', Keyboard::CMD_CAPS
                ),
                array(
                    'a', 's', 'd', 'f', 'g', 'h', 'j', 'k', 'l', ' ', ' ', ' ', '%', '<', '^', ';', '/', ')', '`', Keyboard::CMD_CLEAR
                ),
                array(
                    'z', 'x', 'c', 'v', 'b', 'n', 'm', ' ', ' ', ' ', '.', '-', ':', '|', '~', '*', '=', '@', '_', Keyboard::CMD_SEND
                ),                
            ),
        );
        return $keys[$type];
    }
    
    /**
     * @param array $keyboard
     * @return string
     */
    protected function _transformToMos($keyboard)
    {
        $ret    = '';
        $rows   = count($keyboard);
        $cols   = count($keyboard[0]);
        for($col = 0; $col < $cols; ++$col){
            for($row = 0; $row < $rows; ++$row){
                $ret .= htmlspecialchars($keyboard[$row][$col]) . '&#10;';
            }
        }
        return $ret;
    }
}
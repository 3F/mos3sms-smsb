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

/**
 * Debug option
 */
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors',       'On');
ini_set('log_errors',           'On');
ini_set('log_errors_max_len',   256 * 1024);
ini_set('error_log',            '/tmp/log/mos3_smsb.log');

//enviroment
date_default_timezone_set('Europe/Moscow');

/**
 * load components
 */
require_once MODROOT . '/scripts/system.php';
require_once MODROOT . '/scripts/keyboard.php';
require_once MODROOT . '/scripts/network.php';
require_once MODROOT . '/scripts/handler.php';

/**
 * load main settings
 */
$config = require_once(MODROOT . '/scripts/config.php');
System::init($config);
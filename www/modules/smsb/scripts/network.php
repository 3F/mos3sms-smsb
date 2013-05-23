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


class Remote 
{
    /**
     * last cookies received 
     * @var array
     */
    private $_cookies   = array();
    /**
     * last response headers
     * @var array
     */
    private $_headers   = array();
    
    /**
     * retreive response
     * @param string $url
     * @return string
     */
    public function request($url)
    {
        return $this->_request($url, stream_context_create(array(
            'http' => array(
                'method' => 'GET',
                'header' => 
                    'Referer: '. $url ."\r\n" .
                    $this->cookies(true),
            ),
        )));
    }
    
    /**
     * getting cookies
     * @param boolean $format - returns header format if true
     * @return mixed
     */
    public function cookies($format = false)
    {
        if($format){
            if(count($this->_cookies) < 1){
                return '';
            }
            return 'Cookie: ' . implode('; ', $this->_cookies) . "\r\n";
        }
        return $this->_cookies;
    }
    
    /**
     * sending data by POST method
     * @param \reg\plugin\mos3\smsb\IRemoteParam $data
     * @param string $url
     * @return string
     */
    public function push(IRemoteParam $data, $url)
    {
        return $this->_request($url, stream_context_create(array(
            'http' => array(
                'method' => 'POST',
                'header' => 
                    'Referer: '. $url ."\r\n" .
                    "Content-type: application/x-www-form-urlencoded\r\n" .
                    $this->cookies(true),
                'content' => $data->param(),
            ),
        )));
    }
    
    protected function _foundCookies(&$headers)
    {
        $cookies = array();
        foreach($headers as $header){
            if(preg_match("#Set-Cookie\s*:\s*([^;]+?);#i", $header, $match)){
                $cookies[] = $match[1];
            }
        }
        return $cookies;
    }
    
    protected function _request($url, $context)
    {
        $content        = file_get_contents($url, false, $context);
        $this->_headers = $http_response_header; // PHP variable, see manual
        $this->_cookies = $this->_foundCookies($this->_headers);
        return $content;
    }
}


interface IRemoteParam
{
    /**
     * prepare HTTP data
     * @return string
     */
    public function param();
}

class RemoteParam implements IRemoteParam
{
    private $_data = null;
    public function __construct(array $data)
    {
        $this->_data = $data;
    }

    public function param()
    {
        return http_build_query($this->_data, '', '&');
    }
}
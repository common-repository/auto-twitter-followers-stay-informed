<?php
/*************************
 * bit.ly Class Copyright (c)- 2010 - Ákos Nikházy.
 * All rights reserved.   This program and the accompanying materials
 * are made available under the terms of the 
 * GNU General Public License (GPL) Version 2, June 1991, 
 * which accompanies this distribution, and is available at: 
 * http://www.opensource.org/licenses/gpl-license.php
 
 *It creates a short URL form given URL using bit.ly API. To use it you have to register at bit.ly and then get the API KEY at http://bit.ly/a/your_api_key
 *Version 1.0
**************************/
class bitlyClass
{
    protected $bitlyLogin     = "YOUR BIT.LY LOGIN";
    protected $bitlyApikey     = "YOUR BIT.LY API KEY";
    
    public function __construct($_bitlyLogin = '',$_bitlyApikey = '')
    {
        if($_bitlyLogin != '' && $_bitlyApikey != '')
        {
            $this->bitlyLogin     = $_bitlyLogin;
            $this->bitlyApikey     = $_bitlyApikey;
        }
        
    }
    
    //The shortener function. The url paramater is needed.
    public function getShortURL($url)
    {
        $apiURL     = 'http://api.bit.ly/shorten?version=2.0.1&longUrl='.$url.'&login='.$this->bitlyLogin.'&apiKey='.$this->bitlyApikey;
        $APICall     = file_get_contents($apiURL);
        $bitlyInfo    = json_decode(utf8_encode($APICall),true);
        return ($bitlyInfo['errorCode']==0)?$bitlyInfo['results'][urldecode($url)]['shortUrl']:false;
    }

} 
?>
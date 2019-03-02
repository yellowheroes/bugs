<?php
/*
 * This class wraps HtmlPurifier library for easier object instantiation and use
 * the library can be found in 'vendor' directory
 * http://htmlpurifier.org/docs
 * 
 * usage:
 * $dirtyHtml = "<h1>hello world</h1><script>alert(1);</script>";
 * $purify = new libs\Purify($dirtyHtml);
 * echo '<pre>';
 * var_dump($purify->cleanHtml);
 * echo '</pre>';
 * 
 */
namespace yellowheroes\projectname\system\libs;

class Purify
{
    public $cleanHtml = null;
    
    public function __construct($dirtyHtml = null)
    {
        $config = \HTMLPurifier_Config::createDefault();
        $purifier = new \HTMLPurifier($config);
        $this->cleanHtml = $purifier->purify($dirtyHtml);
    }
}
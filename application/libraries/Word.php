<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
include_once(APPPATH."third_party/PhpWord/Autoloader.php");
use PhpOffice\PhpWord\Autoloader;
use PhpOffice\PhpWord\Settings;
Autoloader::register();
Settings::loadConfig();

class Word extends Autoloader { 

}
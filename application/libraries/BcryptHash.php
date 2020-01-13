<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: rizki
 * Date: 23/08/2019
 * Time: 20:51
 */

Class BcryptHash
{
    function __construct()
    {
        $this->CI =& get_instance();
    }

    public function hashing()
    {
        require_once './vendor/bitmannl/bcrypt/Crypt.php';
        require_once './vendor/bitmannl/bcrypt/Bcrypt.php';
        return new Bcrypt();
    }

}
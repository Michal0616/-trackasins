<?php
/*
 * This is the main cookies class that will create, edit, and destroy cookies
 */
class Cookies
{
    /*
     * This will make the cookie
     * 
     * @var $name
     * @var $data
     * @var $time
     * @var $path
     */
    public function make($name, $data, $time, $path = "/")
    {
        setcookie($name, $data, $time, $path);
    }

    /*
     * This will get information from the cookie
     * 
     * @var $name
     */
    public function get($name)
    {
        if($this->exist($name))
        {
            return $_COOKIE[$name];
        }else{
            return false;
        }
    }

    /*
     * This will check to make sure the cookie exist
     * 
     * @var $name
     */
    public function exist($name)
    {
        if(isset($_COOKIE[$name]))
        {
            return true;
        }else{
            return false;
        }
    }

    /*
     * This will destroy the cookie
     * 
     * @var $name
     */
    public function destroy($name)
    {
        if($this->exist($name))
        {
            unset($_COOKIE[$name]);
            setcookie($name, null, -1, '/');
        }
    }
}
<?php
/*
 * This class is for remembering users logins and allowing them to stay logged in!
 */
class Remember
{
    /*
     * Load up libraries that's gonna be needed in this class
     */
    public function __construct()
    {
        $this->_CI =& get_instance();

        // Load libraries
        $this->_CI->load->library("src/Encryption.php");
        $this->_CI->load->library("src/Cookies.php");

        // Load database
        $this->pdo = $this->_CI->load->database('pdo', true)->conn_id;
    }

    /*
     * This will initiate the new cookie and insert it into the database
     *
     * @var $user_id
     * @var $salt
     */
    public function make($user_id, $salt)
    {
        if(!empty($user_id) && !empty($salt))
        {
            $ip = $_SERVER['SERVER_ADDR'];
            $token = md5($this->_CI->encryption->randomHash());

            // Now insert the stuff in the table
            $query = $this->pdo->prepare("INSERT INTO remember_me VALUES('', :user_id, :token, :ip)");
            if($query->execute(array(
                ':user_id' => $user_id,
                ':token'   => $token,
                ':ip'      => $ip
            )))
            {
                $this->_CI->cookies->make('trackasins_remember', $token, time() + 2 * 7 * 24 * 3600);
            }
        }
    }

    /*
     * This will check to see if the remember me request exist
     *
     * @var $cookie_hash
     */
    public function exist($cookie_hash)
    {
        if(!empty($cookie_hash))
        {
            // Check this hash
            $query = $this->pdo->prepare("SELECT * FROM remember_me WHERE cookie_hash=:cookie_hash");
            $query->execute(array(
                ':cookie_hash' => $cookie_hash
            ));

            if($query->rowCount() == 1)
            {
                return true;
            }else{
                return false;
            }
        }
    }

    /*
     * This will return id of the user
     *
     * @var $cookie_hash
     */
    public function return_id($cookie_hash)
    {
        if(!empty($cookie_hash))
        {
            // Check this hash
            $query = $this->pdo->prepare("SELECT * FROM remember_me WHERE cookie_hash=:cookie_hash");
            $query->execute(array(
                ':cookie_hash' => $cookie_hash
            ));

            if($query->rowCount() == 1)
            {
                $fetch = $query->fetch(PDO::FETCH_ASSOC);

                // Do stuff
                $ip = $fetch['user_given_ip'];

                if($ip == $_SERVER['SERVER_ADDR'])
                {
                    return $fetch['user_id'];
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }
    }

    /*
     * This will destroy the cookie and the "remember me" entry
     * 
     * @cookie_hash
     */
    public function destroy($cookie_hash)
    {
        if(!empty($cookie_hash))
        {
            // Check this hash
            $query = $this->pdo->prepare("SELECT * FROM remember_me WHERE cookie_hash=:cookie_hash");
            $query->execute(array(
                ':cookie_hash' => $cookie_hash
            ));

            if($query->rowCount() == 1)
            {
                $fetch = $query->fetch(PDO::FETCH_ASSOC);

                // Do stuff
                $ip = $fetch['user_given_ip'];

                if($ip == $_SERVER['SERVER_ADDR'])
                {
                    $delete = $this->pdo->prepare("DELETE FROM remember_me WHERE cookie_hash=:cookie_hash AND user_given_ip=:ip");
                    $delete->execute(array(
                        ':cookie_hash' => $cookie_hash,
                        ':ip'          => $ip
                    ));

                    // Delete cookies
                    $this->_CI->cookies->destroy('trackasins_remember');

                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }
    }

    /*
     * This will validate the cookie and the entry
     * 
     * @var @cookie_hash
     */
    public function check($cookie_hash)
    {
        if(!empty($cookie_hash))
        {
            // Check this hash
            $query = $this->pdo->prepare("SELECT * FROM remember_me WHERE cookie_hash=:cookie_hash");
            $query->execute(array(
                ':cookie_hash' => $cookie_hash
            ));

            if($query->rowCount() == 1)
            {
                $fetch = $query->fetch(PDO::FETCH_ASSOC);

                // Do stuff
                $ip = $fetch['user_given_ip'];

                if($ip == $_SERVER['SERVER_ADDR'])
                {
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }
    }
}
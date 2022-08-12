<?php
/*
 * This user class will help with doing easy tasks for users.
 * It will mainly be for helping us with the header part and it will help with other simple thing
 */
class User
{
    private $uid;
    private $unique_id;

    public function __construct()
    {
        $this->_CI =& get_instance();

        // Load libraries
        $this->_CI->load->library("src/Validation.php");
        $this->_CI->load->library("src/Encryption.php");
        $this->_CI->load->library("src/Response.php");

        // Load database
        $this->pdo = $this->_CI->load->database('pdo', true)->conn_id;
    }

    public function IsLoggedIn()
    {
        if(isset($_SESSION))
        {
            // Check to see if the valued are set
            if(isset($_SESSION['uid']) && isset($_SESSION['unique_salt_id']) && isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true)
            {
                // Now lets make sure the user exist based on uid and salt id
                if($this->CheckExist($_SESSION['uid'], $_SESSION['unique_salt_id']) == true)
                {
                    // Let the person do whatever
                    return true;
                }else{
                    $this->_CI->session->unset_userdata('uid');
                    $this->_CI->session->unset_userdata('unique_salt_id');
                    $this->_CI->session->unset_userdata('logged_in');

                    redirect('/', 'location');
                }
            }else{
                $this->_CI->session->unset_userdata('uid');
                $this->_CI->session->unset_userdata('unique_salt_id');
                $this->_CI->session->unset_userdata('logged_in');

                redirect('/', 'location');
            }
        }else{
            $this->_CI->session->unset_userdata('uid');
            $this->_CI->session->unset_userdata('unique_salt_id');
            $this->_CI->session->unset_userdata('logged_in');

            // Now redirect
            redirect('/', 'location');
        }
    }

    public function CheckExist($uid, $salt = "")
    {
        if(!empty($uid))
        {
            // Now load the vars
            $this->_uid = $uid;

            if($salt != "")
            {
                // Unencrypt the var now
//                $this->_unique_id = $this->_CI->encryption->decryptText($salt);
            }


            // Now check
            if(isset($this->_unique_id) && $this->_unique_id != "")
            {
                $query = $this->pdo->prepare("SELECT * FROM users WHERE ID=:uid AND unique_string_id=:unique_id AND activated='1'");
            }else{
                $query = $this->pdo->prepare("SELECT * FROM users WHERE ID=:uid AND activated='1'");
            }

            // Now execute the query
            if(isset($this->_unique_id) && $this->_unique_id != "")
            {
                if ($query->execute(array(':uid' => $this->_uid, ':unique_id' => $this->_unique_id)))
                {
                    if ($query->rowCount() == 1)
                    {
                        // Means this user exist and is activated
                        return true;
                    } else {
                        // Means theres something up with thisuser
                        return false;
                    }
                } else {
                    return false;
                }
            }else{
                if ($query->execute(array(':uid' => $this->_uid)))
                {
                    if ($query->rowCount() == 1)
                    {
                        // Means this user exist and is activated
                        return true;
                    } else {
                        // Means theres something up with thisuser
                        return false;
                    }
                } else {
                    return false;
                }
            }
        }else{
            return false;
        }
    }
    
    public function GetInfo($table = "users", $column, $uid)
    {

        if(!empty($table) && !empty($column) && !empty($uid))
        {
            // Make sure they exist

            if($this->CheckExist($uid) == true)
            {

                $query = $this->pdo->prepare("SELECT unique_string_id, " . $column . " FROM " . $table . " WHERE ID=:uid");
                $query->execute(array(':uid' => $uid));
                
                if($query->rowCount() == 1)
                {
                    $fetch = $query->fetch(PDO::FETCH_ASSOC);
                    $this->_unique_id = $fetch['unique_string_id'];
                    return $fetch[$column];
                }else{
                    return '';
                }
            }else{
                return '';
            }
        }
    }
}
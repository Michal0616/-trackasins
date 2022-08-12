<?php
/*
 * So this will hold the entire settings system
 */
class SettingsSystem
{
    private $response = array();

    /* --- Profile picture upload --- */
    private $pp_name;

    /* --- Change Basic Information --- */
    private $firstname;
    private $email;
    private $company;
    private $seller_id;
    private $phone_number;
    private $location;

    /* --- Upgrade Plan -- */
    private $plan;

    /* --- Change Notifications --- */
    private $notifications;

    /* --- Change Password --- */
    private $current_password;
    private $new_password;
    private $confirm_password;

    /* --- Amazon API change --- */
    private $api_connection;
    private $user_seller_id;
    private $marketplace_id;
    private $associate_tag;
    private $dev_account_number;
    private $access_key_id;
    private $secret_key;

    public function __construct()
    {
        $this->_CI =& get_instance();

        // Load libraries
        $this->_CI->load->library("src/Validation.php");
        $this->_CI->load->library("src/Response.php");
        $this->_CI->load->library("src/Encryption.php");
        $this->_CI->load->library("src/User.php");
        $this->_CI->load->library("src/Upload.php");

        // Load database
        $this->pdo = $this->_CI->load->database('pdo', true)->conn_id;
    }

    /*
     * This will remove the users profile pic
     *
     */
    public function removeProfilePic()
    {
        if(isset($_SESSION['uid']))
        {
            // Just remove the picture
            $update = $this->pdo->prepare("UPDATE users SET profile_pic='default_pic.jpg' WHERE ID=:uid");
            if($update->execute(array(':uid' => $_SESSION['uid'])))
            {
                $this->_response['code'] = 1;
                $this->_response['link'] = base_url('assets2/user_data/default_pic.jpg');
                $this->_response['string'] = "Your profile picture has been updated!";

                echo json_encode($this->_response);
            }
        }
    }

    /*
     * This will edit the persons Amazon api settings
     *
     * @var $data
     */
    public function changeAmazonAPI($data)
    {
        if(!empty($data) && is_array($data))
        {
            // fill in the vars
            $this->_api_connection = $this->_CI->validation->santitize($data['api_connection']);
            $this->_user_seller_id = $this->_CI->validation->santitize($data['seller_id']);
            $this->_marketplace_id = $this->_CI->validation->santitize($data['marketplace_id']);
            $this->_associate_tag = $this->_CI->validation->santitize($data['associate_tag']);
            $this->_dev_account_number = $this->_CI->validation->santitize($data['dev_account_number']);
            $this->_access_key_id = $this->_CI->validation->santitize($data['access_key_id']);
            $this->_secret_key = $this->_CI->validation->santitize($data['secret_key']);

            // Now make sure the seller id and api connection vars arent empty
            if(!empty($this->_api_connection) && !empty($this->_user_seller_id))
            {
                // Now lets just update everything
                $check = $this->pdo->prepare("SELECT * FROM amazonapi WHERE UserID=:uid");
                $check->execute(array(
                    ':uid' => $_SESSION['uid']
                ));
                if($check->rowCount() == 1){
                    $update = $this->pdo->prepare("UPDATE amazonapi SET connection=:connection, SellerID=:seller_id, MarketPlaceID=:marketplace_id, AssociateTagID=:associate_tag, DeveloperAccountNumber=:dev_account_number, AccessKeyID=:access_key_id, SecretKey=:secret_key WHERE UserID=:uid");
                } else {
                    $update = $this->pdo->prepare("INSERT INTO amazonapi (connection, SellerID, MarketPlaceID, AssociateTagID, DeveloperAccountNumber, AccessKeyID, SecretKey, UserID) VALUES(:connection,:seller_id,:marketplace_id,:associate_tag,:dev_account_number,:access_key_id,:secret_key, :uid)");
                }

                if($update->execute(array(
                    ':connection' => $this->_api_connection,
                    ':seller_id'  => $this->_user_seller_id,
                    ':marketplace_id' => $this->_marketplace_id,
                    ':associate_tag' => $this->_associate_tag,
                    ':dev_account_number' => $this->_dev_account_number,
                    ':access_key_id' => $this->_access_key_id,
                    ':secret_key' => $this->_secret_key,
                    ':uid' => $_SESSION['uid']
                ))){
                    $this->_response['code'] = 1;
                    $this->_response['string'] = "Your info has been updated!";
                    echo json_encode($this->_response);
                    return false;
                }else{
                    $this->_response['code'] = 0;
                    $this->_response['string'] = "Error Occurred!";
                    echo json_encode($this->_response);
                    return false;
                }
            }else
            {
                $this->_response['code'] = 0;
                $this->_response['string'] = "Please make sure your seller id isn't empty!";
                echo json_encode($this->_response);
                return false;
            }
        }
    }
    /*
     * This will change the users email and phone number!
     *
     * @var $email
     * @var $phone
     */
    public function changeSecuritySettings($email, $phone){
        if(!empty($email) && !empty($phone)){
            $check = $this->pdo->prepare("SELECT * FROM users WHERE ID=:uid");
            $check->execute(array(
                ':uid' => $_SESSION['uid']
            ));
            if($check->rowCount() == 1){
                $query = $this->pdo->prepare("UPDATE users SET phone=:phone, email =:email WHERE ID=:unique_id");
                if ($query->execute(array(':phone' => $phone, 'email' => $email,':unique_id' => $_SESSION['uid'])))
                {
                    $this->_response['code'] = 1;
                    $this->_response['string'] = "Your phone number and login email has been updated!";
                    echo json_encode($this->_response);
                    return false;
                } else {
                    $this->_response['code'] = 0;
                    $this->_response['string'] = "Error Occurred";
                    echo json_encode($this->_response);
                    return false;
                }
            } else {
                $this->_response['code'] = 0;
                $this->_response['string'] = "Error Occurred";
                echo json_encode($this->_response);
                return false;
            }

        } else{
            $this->_response['code'] = 0;
            $this->_response['string'] = "Error Occurred";
            echo json_encode($this->_response);
            return false;
        }
    }
    /*
     * This will change the users login email address
     * @var $current_email
     * @var $new_email
     * @var $confirm_new_email
     *
     */

    public function changeEmail ($current_email, $new_email, $confirm_new_email)
    {
        if(!empty($current_email) && !empty($new_email) && !empty($confirm_new_email))
        {
            $this->_current_email =$current_email;
            $this->_new_email =$new_email;
            $this->_confirm_new_email =$confirm_new_email;
            if($this->_current_email ==  $this->_CI->user->GetInfo('users', 'email', $_SESSION['uid']))
            {
                if ($this->_new_email == $this->_confirm_new_email)
                {
                    $check = $this->pdo->prepare("SELECT * FROM users WHERE email=:new_email");
                    $check->execute(array(':new_email' => $new_email));
                    if($check->rowCount() == 1){
                        $this->_response['code'] = 0;
                        $this->_response['string'] = "Your current email exist.";
                        echo json_encode($this->_response);
                        return false;
                    } else {
                        $query = $this->pdo->prepare("UPDATE users SET email=:new_email WHERE ID=:unique_id");
                        if ($query->execute(array(':new_email' => $this->_confirm_new_email, ':unique_id' => $_SESSION['uid'])))
                        {
                            echo $this->_CI->response->make("Your email address has been changed!", 'JSON', 1);

                            return $this->_CI->response->make("Your email address has been changed!", 'JSON', 1);
                        } else {
                            echo $this->_CI->response->make("Error has occurred!", 'JSON', 0);
                            return false;
                        }
                    }

                }else {
                    echo $this->_CI->response->make("These email doesn't match! They must match.", 'JSON', 0);
                    return false;
                }
            } else {
                $this->_response['code'] = 0;
                $this->_response['string'] = "Your current email does not match the one you supplied!";
                echo json_encode($this->_response);
                return false;
            }
        }else {
            $this->_response['code'] = 0;
            $this->_response['string'] = "Error Occurred";
            echo json_encode($this->_response);
            return false;
        }
    }

    /*
     * This will change the users password!
     *
     * @var $current
     * @var $new
     * @var $confirm
     */
    public function changePassword($current, $new, $confirm)
    {
        if(!empty($current) && !empty($new) && !empty($current))
        {
            $this->_current_password =md5($current);
            $this->_new_password = md5($new);
            $this->_confirm_password =md5($confirm);

            if ($this->_CI->user->CheckExist($_SESSION['uid'], $_SESSION['unique_salt_id']))
            {
                // lets make sure this current password equals the users current password
                if ($this->_current_password == $this->_CI->user->GetInfo('users', 'password', $_SESSION['uid']))
                {
                    // Now change the password
                    if ($this->_new_password == $this->_confirm_password)
                    {
                        // one last check
                        if ($this->_new_password != "" && $this->_confirm_password != "")
                        {
                            // Now update the stuff
                            $query = $this->pdo->prepare("UPDATE users SET password=:new_pass WHERE ID=:unique_id");
                            if ($query->execute(array(':new_pass' => $this->_confirm_password, ':unique_id' => $_SESSION['uid'])))
                            {
                                echo $this->_CI->response->make("Your password has been changed!", 'JSON', 1);
                                return $this->_CI->response->make("Your password has been changed!", 'JSON', 1);
                                
                            } else {
                                echo $this->_CI->response->make("Error has occurred!", 'JSON', 0);
                                return false;
                            }
                        } else {
                            echo $this->_CI->response->make("Error has occurred!", 'JSON', 0);
                            return false;
                        }
                    } else {
                        echo $this->_CI->response->make("These passwords dont match! They must match.", 'JSON', 0);
                        return false;
                    }
                } else {
                    $this->_response['code'] = 0;
                    $this->_response['string'] = "Your current password does not match the one you supplied!";
                    echo json_encode($this->_response);
                    return false;
                }
            }else{
                $this->_response['code'] = 0;
                $this->_response['string'] = "This account dosent exist!";

                echo json_encode($this->_response);
                return false;
            }
        }else{
            $this->_response['code'] = 0;
            $this->_response['string'] = "Error Occurred";
            echo json_encode($this->_response);
            return false;
        }
    }

    /*
     * This will change the user notifications settings
     *
     * @var notifications
     * @var email
     * @phone
     */
    public function changeNotifications($notifications, $email, $phone_number)
    {
        if(!empty($notifications) && !empty($email) && !empty($phone_number)) {
            $this->_notifications = $notifications;
            $this->_email = $email;

            if ($this->_CI->user->CheckExist($_SESSION['uid'], $_SESSION['unique_salt_id'])) {
                // make sure its equal to 'yes' or 'no'
                if ($this->_notifications == "yes" or $this->_notifications == "no") {
                    $check = $this->checkEmail($email);

                    if ($check['code'] == 1) {
                        // Now do what we need to do
                        $query = $this->pdo->prepare("UPDATE users SET notifications=:new_notes, phone=:phone WHERE ID=:uid");
                        if ($query->execute(array(':new_notes' => $this->_notifications, ':phone' => $phone_number, ':uid' => $_SESSION['uid']))) {
                            $this->_response['code'] = 1;
                            $this->_response['string'] = "Your notification settings has been updated!";
                            echo json_encode($this->_response);
                            return false;
                        } else {
                            $this->_response['code'] = 0;
                            $this->_response['string'] = "Error Occurred";
                            echo json_encode($this->_response);
                            return false;
                        }
                    } else if ($check['code'] == 0) {
                        // Means this email already exist
                        echo json_encode($check);
                        return false;
                    }
                } else {
                    $this->_response['code'] = 0;
                    $this->_response['string'] = "Invalid Request";
                    echo json_encode($this->_response);
                }
            }else{
                $this->_response['code'] = 0;
                $this->_response['string'] = "This account dosent exist!";

                echo json_encode($this->_response);
                return false;
            }
        }else{
            $this->_response['code'] = 0;
            $this->_response['string'] = "Error Occurred";
            echo json_encode($this->_response);
            return false;
        }
    }

    /*
     * This will change the users plan and upgrade it for them!
     *
     * @var $newPlan
     */
    public function changePlan($plan)
    {
        if(!empty($plan))
        {
            $this->plan = $this->_CI->validation->santitize($plan);

            // Lets make sure this is a valid plan/subscription
            $query = $this->pdo->prepare("SELECT * FROM subscriptions WHERE PlanName=:plan");
            if($query->execute(array(':plan' => $this->plan)))
            {
                // If it exist then were god
                if($query->rowCount() == 1)
                {
                    // Now lets just update the users plan!
                    $update = $this->pdo->prepare("UPDATE users SET plan=:newplan WHERE ID=:uid");
                    if($update->execute(array(':newplan' => $this->plan, ':uid' => $_SESSION['uid'])))
                    {
                        // We good now
                        $this->_response['code'] = 1;
                        $this->_response['string'] = "Your account has been updated!";
                        echo json_encode($this->_response);
                    }else
                    {
                        $this->_response['code'] = 0;
                        $this->_response['string'] = "Error Occurred";
                        echo json_encode($this->_response);
                    }
                }else
                {
                    $this->_response['code'] = 0;
                    $this->_response['string'] = "This plan does not exist!";
                    echo json_encode($this->_response);
                }
            }else
            {
                $this->_response['code'] = 0;
                $this->_response['string'] = "Error Occurred";
                echo json_encode($this->_response);
            }
        }
    }

    /*
     * This will check the email to see if it needs to be changed or not
     *
     * @var $email
     */
    private function checkEmail($email)
    {
        if($email != "")
        {
            // Most importantly we need to see if the person is sending the same email or if its different
            if($this->_CI->user->GetInfo('users', 'email', $_SESSION['uid']) != $this->_email)
//            if($this->_CI->encryption->decryptText($this->_CI->user->GetInfo('users', 'email', $_SESSION['uid'])) != $this->_email)
            {
                // This means we need to change the email but make sure it dosent exist
                $check =  $this->pdo->prepare("SELECT * FROM users WHERE email=:email");
                if($check->execute(array(':email' => $this->_email)))
                {
                    // If there is no email like that then change it and run the rest of the script
                    if($check->rowCount() == 0)
                    {
                        $change = $this->pdo->prepare("UPDATE users SET email=:email WHERE ID=:uid");
//                        if($change->execute(array(':email' => $this->_CI->encryption->encryptText($this->_email), ':uid' => $_SESSION['uid'])))
                        if($change->execute(array(':email' => ($this->_email), ':uid' => $_SESSION['uid'])))
                        {
                            // Now run the rest of the script
                            $this->_response['code'] = 1;
                            return $this->_response;
                        }else{
                            $this->_response['code'] = 0;
                            $this->_response['string'] = "Error Occurred";
                            return $this->_response;
                        }
                    }else {
                        $this->_response['code'] = 0;
                        $this->_response['string'] = "This email already exist!";
                        return $this->_response;
                    }
                }else{
                    $this->_response['code'] = 0;
                    $this->_response['string'] = "Error Occurred";
                    return $this->_response;
                }
            }else{
                $this->_response['code'] = 1;
                return $this->_response;
            }
        }
    }
    /*
     * This will check the email to see if it needs to be changed or not
     *
     * @var $email
     */

    private function checkAdditionalEmail($email){
        if($email != ""){
            if($this->_CI->user->GetInfo('users', 'email', $_SESSION['uid']) != $email){
                $check =  $this->pdo->prepare("SELECT * FROM users WHERE email=:email");
                if($check->execute(array(':email' => $email))) {
                    // If there is no email like that then change it and run the rest of the script
                    if ($check->rowCount() == 0) {
                        $currentAdditionalEmail = $this->_CI->user->GetInfo('users', 'email', $_SESSION['uid']);
                        if($currentAdditionalEmail != $email){
                            $change = $this->pdo->prepare("UPDATE users SET email=:email WHERE ID=:uid");
                            if($change->execute(array(':email' => ($this->_email), ':uid' => $_SESSION['uid'])))
                            {
                                // Now run the rest of the script
                                $this->_response['code'] = 1;
                                $this->_response['change_email'] = 1;
                                return $this->_response;
                            }else{
                                $this->_response['code'] = 0;
                                $this->_response['string'] = "Error Occurred";
                                return $this->_response;
                            }
                        } else {
                            $this->_response['code'] = 1;
                            return $this->_response;
                        }


                    } else {
                        $this->_response['code'] = 0;
                        $this->_response['string'] = "This email already exist!";
                        return $this->_response;
                    }

                } else{
                    $this->_response['code'] = 0;
                    $this->_response['string'] = "Error Occurred";
                    return $this->_response;
                }

            } else {
                $this->_response['code'] = 1;
                return $this->_response;
            }
        }
    }

    /*
     * Change Basic Information
     *
     * @var $uid
     * @file $picture
     */
    public function changeBasicInfo($data)
    {
        if(!empty($data) && is_array($data))
        {
            // Load the variables
            $this->_firstname = $this->_CI->validation->santitize($data['firstname']);
            $this->_email = $this->_CI->validation->santitize($data['email']);
            $this->_company = $this->_CI->validation->santitize($data['company']);
           // $this->_seller_id = $this->_CI->validation->santitize($data['seller_id']);
            $this->_phone_number = $this->_CI->validation->santitize($data['phone']);

            // Now lets make sure this user exist
            if($this->_CI->user->CheckExist($_SESSION['uid'], $_SESSION['unique_salt_id']))
            {
                // Most importantly we need to see if the person is sending the same email or if its different
                $check = $this->checkAdditionalEmail($this->_email);

                if($check['code'] == 1)
                {
                    // Means we can run the rest of the script and just update everything
                    $update = $this->pdo->prepare("UPDATE users SET fname=:fname, phone=:phone, company=:company WHERE ID=:uid");
                    if($update->execute(array(
                        ':fname' => $this->_firstname,
                        //':seller_id' => $this->_seller_id,
                        ':phone' => $this->_phone_number,
                        ':company' => $this->_company,
                        ':uid' => $_SESSION['uid']
                    )))
                    {
                        $this->_response['code'] = 1;
                        $this->_response['string'] = 'Your information has been updated!';

                        echo json_encode($this->_response);
                    }else{
                        $this->_response['code'] = 0;
                        $this->_response['string'] = 'Error Occurred';

                        echo json_encode($this->_response);
                        return false;
                    }
                }else if($check['code'] == 0){
                    // Means this email already exist
                    echo json_encode($check);
                    return false;
                }
            }else{
                $this->_response['code'] = 0;
                $this->_response['string'] = "This account dosent exist!";

                echo json_encode($this->_response);
                return false;
            }
        }else
        {
            $this->_response['code'] = 0;
            $this->_response['string'] = "Invalid Request";

            echo json_encode($this->_response);
        }
    }

    /*
     * Profile Picture upload
     *
     * @var $uid
     * @file $picture
     */
    public function ChangeProfilePicture($picture)
    {
        if(!empty($picture))
        {
            // Make sure this person exists
            if($this->_CI->user->CheckExist($_SESSION['uid'], $_SESSION['unique_salt_id']) == 1)
            {
                // Now encrypt the users profile picture
//                $this->_pp_name = $this->_CI->encryption->encryptText($picture['name']);
                $this->_pp_name = time().$picture['name'];

                if($this->_pp_name != "")
                {
                    // Now we can upload this picture
                    $upload = $this->_CI->upload->initiate($picture, 'photo', array(
                        'photoRootLocation' =>  realpath(FCPATH) . '/assets2'. '/user_data/',
                        'photoPublicLocation' => base_url() . 'assets2/user_data/'),
		    'regular');
		    //print_r($upload);
                    if($upload['code'] == 1)
                    {
                        // Now lets go ahead and add the new profile pics name to the database
                        $this->_pp_name = $upload['photo_data']['photoEncryption'];

                        // Make the query
                        $query = $this->pdo->prepare("UPDATE users SET profile_pic=:new_pic WHERE ID=:uid");
                        if($query->execute(array(':new_pic' => $this->_pp_name, ':uid' => $_SESSION['uid'])))
                        {
                            $this->_response['code'] = 1;
                            $this->_response['link'] = $upload['photo_data']['photoPublicLocation'];
                            $this->_response['string'] = "Your profile picture has been updated!";

                            echo json_encode($this->_response);
                        }else{
                            $this->_response['code'] = 0;
                            $this->_response['string'] = 'Error Occurred';

                            echo json_encode($this->_response);
                        }
                    }else{
                        $this->_response['code'] = $upload['code'];
                        $this->_response['string'] = $upload['status'];

                        echo json_encode($this->_response);
                    }
                }else{
                    $this->_response['code'] = 0;
                    $this->_response['string'] = "Invalid Request";

                    echo json_encode($this->_response);
                }
            }else{
                $this->_response['code'] = 0;
                $this->_response['string'] = "Invalid Request";

                echo json_encode($this->_response);
            }
        }else{
            $this->_response['code'] = 0;
            $this->_response['string'] = "Please upload your photo!";

            echo json_encode($this->_response);
        }
    }
}

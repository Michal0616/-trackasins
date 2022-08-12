<?php
class Upload
{
    private $file;
    public $fileName;
    private $fileType;
    private $fileTmpLocation;
    public $fileExtension;
    public $fileSize;

    public $fileRootPathThumb50;
    public $fileRootPathThumb80;
    public $fileRootPathThumb200;

    public $encryptedThumbName;

    private $allowedPhotoTypes = array('image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/ttf');
    private $allowedPhotoExt = array('jpg', 'jpeg', 'png', 'gif');

    private $allowedVideoTypes = array('video/mp4', 'video/wmv', 'video/ogg', 'video/mov');
    private $allowedVideoPhotoExt = array('mov', 'wov', 'mpeg', 'mp4');

    private $type = array('photo', 'video');

    private $fileRootPath;
    public $encryptedFileName = array('50' => '', '80' => '', '150' => '');

    public $filePublicPath;

    public function __construct()
    {
        $this->_CI =& get_instance();

        // Load libraries
        $this->_CI->load->library("src/Validation.php");
        $this->_CI->load->library("src/Encryption.php");

        // Load database
        $this->pdo = $this->_CI->load->database('pdo', true)->conn_id;
    }

    public function initiate($file, $type, $uploadLocation = array(), $return = 'regular', $thumb = false)
    {
        if (isset($file)) {
            // Set all of the vars
            $this->file = $file;
            $this->fileName = $this->_CI->validation->clean_file_name($file['name']);
            $this->fileType =  $this->_CI->validation->santitize($file['type']);
            $this->fileTmpLocation =  $this->_CI->validation->santitize($file['tmp_name']);
            $this->fileSize =  $this->_CI->validation->santitize($file['size']);

            // Get the file extension
            $e = explode(".", $this->fileName);
            $this->fileExtension = strtolower(end($e));

            $this->encryptedFileName = md5($this->_CI->encryption->randomHash() . $this->_CI->encryption->randomHash()) . rand(0, 10000) . "." . $this->fileExtension;

            if ($type == 'photo') {
                // Means its a photo we are uploading, check the allowed types
                if (in_array($this->fileType, $this->allowedPhotoTypes) && in_array($this->fileExtension, $this->allowedPhotoExt)) {
                    $this->fileRootPath = $uploadLocation['photoRootLocation'] . $this->encryptedFileName;
                    $this->filePublicPath = $uploadLocation['photoPublicLocation'] . $this->encryptedFileName;

                    // Compress
                    $info = getimagesize($this->fileTmpLocation);

                    if ($info['mime'] == 'image/jpeg') {
                        $image = imagecreatefromjpeg($this->fileTmpLocation);

                    } else if ($info['mime'] == 'image/gif') {
                        $image = imagecreatefromgif($this->fileTmpLocation);

                    } else if ($info['mime'] == 'image/png') {
                        $image = imagecreatefrompng($this->fileTmpLocation);
                    }

                    // Now rotate if need be
                    // Means we got a correct file type
                    /*if(move_uploaded_file($this->fileTmpLocation, $this->fileRootPath))
                    {*/
                    if ($this->fileExtension != "gif") {
                        if (imagejpeg($image, $this->fileRootPath, '70')) {
                            chmod($this->fileRootPath, 0777);

                            // Now see if we have thumbnails
                            if($thumb == true)
                            {
                                $this->fileRootPathThumb80 = $uploadLocation['photoRootLocation'] . 'thumb80' . '.' . $this->encryptedFileName;
                                $this->fileRootPathThumb200 = $uploadLocation['photoRootLocation'] . 'thumb200' . '.' . $this->encryptedFileName;

                                self::makeThumbnail($this->fileTmpLocation, $this->fileRootPathThumb80, 80);
                                self::makeThumbnail($this->fileTmpLocation, $this->fileRootPathThumb200, 200);
                            }
                            // Means everything went great, now return the files location
                            if ($return == 'regular') {
                                return array('code' => 1, 'photo_data' => array('photoName' => $this->fileName, 'photoType' => $this->fileType, 'photoEncryption' => $this->encryptedFileName, 'photoPublicLocation' => $this->filePublicPath, 'photoRootLocation' => $this->fileRootPath));
                            } else {
                                echo json_encode(array('photo_data' => array('photoName' => $this->fileName, 'photoType' => $this->fileType, 'photoPublicLocation' => $this->filePublicPath, 'photoRootLocation' => $this->fileRootPath)));
                                return false;
                            }
                        } else {
                            // Means we dont, send a error back in the correct format
                            if ($return == 'regular') {
                                return array('status' => 'Error uploading file', 'code' => 0);
                            } else {
                                echo json_encode(array('message' => 'Error uploading file', 'success' => false));
                                return false;
                            }
                        }
                    } else if ($this->fileExtension == "gif") {
                        if (move_uploaded_file($this->fileTmpLocation, $this->fileRootPath)) {

                            chmod($this->fileRootPath, 0777);
                            // Means everything went great, now return the files location
                            if ($return == 'regular') {
                                return array('code' => 1, 'photo_data' => array('photoName' => $this->fileName, 'photoType' => $this->fileType, 'photoEncryption' => $this->encryptedFileName, 'photoPublicLocation' => $this->filePublicPath, 'photoRootLocation' => $this->fileRootPath));
                            } else {
                                echo json_encode(array('photo_data' => array('photoName' => $this->filename, 'photoType' => $this->fileType, 'photoPublicLocation' => $this->filePublicPath, 'photoRootLocation' => $this->fileRootPath)));
                                return false;
                            }
                        } else {
                            // Means we dont, send a error back in the correct format
                            if ($return == 'regular') {
                                return array('status' => 'Error uploading file', 'code' => 0);
                            } else {
                                echo json_encode(array('message' => 'Error uploading file', 'success' => false));
                                return false;
                            }
                        }
                    }
                } else {
                    // Means we dont, send a error back in the correct format
                    if ($return == 'regular') {
                        //echo "Please upload a photo thats either a jpg, png or gif";
                        return array('status' => 'Please upload a photo thats either a jpg, png or gif', 'code' => 0);
                    } else if ($return == 'JSON') {
                        echo json_encode(array('message' => 'Please upload a photo thats either a jpg, png or gif', 'success' => false));
                        return false;
                    }
                }
            } else if($type == "file"){
                if(!in_array($this->fileExtension, $this->allowedPhotoExt) && !in_array($this->fileExtension, $this->allowedVideoPhotoExt))
                {
                    $this->fileRootPath = $uploadLocation['photoRootLocation'] . $this->encryptedFileName;
                    $this->filePublicPath = $uploadLocation['photoPublicLocation'] . $this->encryptedFileName;

                    if (move_uploaded_file($this->fileTmpLocation, $this->fileRootPath)) {

                        chmod($this->fileRootPath, 0777);
                        // Means everything went great, now return the files location
                        return array('photo_data' => array('photoName' => $this->fileName, 'photoSize' => Validation::byte_convert($this->fileSize), 'photoExt' => $this->fileExtension, 'photoType' => $this->fileType, 'fileEncryption' => $this->encryptedFileName, 'photoPublicLocation' => $this->filePublicPath, 'photoRootLocation' => $this->fileRootPath));
                    }
                }else{
                    echo json_encode(array('status' => 'Use the photo or video uploader to upload photos and videos!', 'code' => 0));
                    return false;
                }
            }
        } else {
            echo 'data_not_recieved';
            return false;
        }
    }

    static public function makeThumbnail($src, $dest, $desired_width)
    {
        /* read the source image */
        $source_image = imagecreatefromjpeg($src);
        $width = imagesx($source_image);
        $height = imagesy($source_image);

        /* find the "desired height" of this thumbnail, relative to the desired width  */
        $desired_height = floor($height * ($desired_width / $width));

        /* create a new, "virtual" image */
        $virtual_image = imagecreatetruecolor($desired_width, $desired_height);

        /* copy source image at a resized size */
        imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);

        /* create the physical thumbnail image to its destination */
        imagejpeg($virtual_image, $dest, '100');
    }
}

?>

<?php

class BlobService {

    private $cache;
    private static $instance;

    public function __construct() {
        $this->cache = array();
    }

    public static function GetInstance() {
        if (self::$instance == null) {
            self::$instance = new BlobService();
        }
        return self::$instance;
    }

    public static function deleteFile($blobKey) {
        if (file_exists($blobKey)) {
            unlink($blobKey);
        }
    }

    public static function uploadFile($destination, $file) {
        if ($_FILES[$file]['error'] == 0) {
            if (move_uploaded_file($_FILES[$file]['tmp_name'], $destination)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

}

?>

<?php
App::import('Behavior', 'Uploadable');
class ImageUploadableBehavior extends UploadableBehavior {
    // Allow these image mime-types, all others will be rejected
    private $valid_mime_types = array('image/jpeg', 'image/png', 'image/gif');
    private $uploadName = '';
    private $contentField = '';

    public function setup(&$model, $config = array()) {
        parent::setup($model, $config);
        $this->subfolderName = 'images';
        if (isset($config['subfolderName'])) {
            $this->subfolderName = $config['subfolderName'];
        }
        if (isset($config['uploadName'])) {
            $this->uploadName = $config['uploadName'];
        }
        if (isset($config['contentField'])) {
            $this->contentField = $config['contentField'];
        }
    }

    public function validateImageProvided(&$model, $data) {
        $field_name = array_pop(array_keys($data));
        $filesFound = $this->_fileExtists($field_name);
        $imageProvided = !empty($data[$field_name]['tmp_name']);
        return $imageProvided || !empty($filesFound);
    }

    /**
     * Validation method that makes sure that the data corresponds to a real image.
     * Requires GD installed
     *
     * @param array $data file data
     * @return void
     * @author Joaquin Windmuller
     */
    public function validateImage(&$model, $data) {
        $field_name = array_pop(array_keys($data));
        if (empty($data[$field_name]['tmp_name'])) {
            return $this->validateImageProvided($model, $data);
        }
        $data = $data[$field_name];

        $rule = func_get_args();
        unset($rule[0], $rule[1]);
        $rule = array_values($rule);
        
        $width = null;
        $height= null;
        if (!empty($rule)) {
            if (isset($rule[0])) {
                $width = $rule[0];
            }
            if (isset($rule[1])) {
                $height= $rule[1];
            }
        }

        $is_required = isset($rule['required']) && $rule['required'];
        // No upload
        if ($data['size'] == 0) {
            if ($is_required) {
                return false;
            }
        }
        // Only accept real uploads
        if (!$this->_isUploadedFile($data, $is_required)) {
            return false;
        }
                
        $filename = $data['tmp_name'];

        // Catch I/O Errors.
        if (!is_readable($filename)) {
            $this->log(__METHOD__." failed to read input file: {$filename}");
            return false;
        }

        // Retrieve the MimeType of Image, if none is returned, it's invalid
        if (!$mime_type = $this->getImageMimeType($filename)) {
            $this->log(__METHOD__." Uploaded file does not have a mime-type");
            return false;
        }
        // Check the MimeType against the array of valid ones specified above
        if (!in_array($mime_type, $this->valid_mime_types)) {
            $this->log(__METHOD__." Uploaded image has rejected Mime Type: {$mime_type}");
            return false;
        }

        if (!$this->__getImageHandleFromFile($filename)) {
            return false;
        }
        if (is_numeric($width) || is_numeric($height)) {
            $size = getimagesize($filename);
            if (is_numeric($width)) { 
                $imageWidthCorrect = $size[0] <= $width+10 && $size[0] >= $width-10;
                var_dump(!$imageWidthCorrect);
                if (!$imageWidthCorrect) {
                    return false;
                }
            }
            if (is_numeric($height)) {
                $imageHeightCorrect = $size[1] <= $height+10 && $size[1] >= $height-10;
                // var_dump(!$imageHeightCorrect);
                if (!$imageHeightCorrect) {
                    return false;
                }
            }
        }
        $ext = $this->extension($mime_type);
        if (!empty($this->contentField)) {
            $model->data[$model->alias][$this->contentField] = $ext;
        }
        return $this->_upload($field_name, $ext, $this->uploadName);
    }

    public function extension($mime_type) {
        $ext = str_replace('image/', '', $mime_type);
        $ext = $ext == 'jpeg' ? 'jpg' : $ext;
        return $ext;
    }

    /**
     * Function that returns the mime type as defined by LibGD
     *
     * @param string $filename path to file
     * @return mixed string mime type if file is image, false if not.
     * @author Joaquin Windmuller
     */
    public function getImageMimeType($filename) {
        // If this error is thrown LibGD is not installed on your server.
        if (!function_exists('getimagesize')) {
            $this->log(__METHOD__." LibGD PHP Extension was not found, please refer to http://www.php.net/manual/en/book.image.php");
            exit();
        }
        $result = getimagesize($filename);
        if (isset($result['mime'])) {
            return $result['mime'];
        }
        return false;
    }

    /**
     * Returns a file handler for an image. Support jpg, gif and png.
     *
     * @param string $filename path to file
     * @return mixed file handler or false if the mime type is not supported or on errors.
     * @author Joaquin Windmuller
     */
    function __getImageHandleFromFile($filename) {
        if (!is_readable($filename)) {
            $this->log(__METHOD__." failed to read input file: {$filename}");
            return false;
        }

        // Retrieve the MimeType of Image, if none is returned, it's invalid
        if (!$mime_type = $this->getImageMimeType($filename)) {
            $this->log(__METHOD__." failed to assertain MimeType of {$filename}");
            return false;
        }
        $mime_type = str_replace('image/', '', $mime_type);
        switch ($mime_type) {
            case 'jpeg':
            case 'gif':
            case 'png':
                $function = 'imagecreatefrom' . $mime_type;
                if (!function_exists($function)) {
                    $this->log(__METHOD__." {$function} not found, install LibGD");
                }
                $handle = @$function($filename);
                break;
            default:
                $this->log(__METHOD__." Didn't know how to handle MimeType: {$mime_type}");
                $handle = false;
                break;
        }
        return $handle;
    }

    public function contentsForData($model, $dataForField) {
        if (empty($dataForField['tmp_name'])) {
            return false;
        }
        return $this->extension($this->getImageMimeType($dataForField['tmp_name']));
    }
}
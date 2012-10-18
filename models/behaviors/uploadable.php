<?php
class UploadableBehavior extends ModelBehavior {
    protected $model;
    protected $subfolderName = '';

    public function setup(&$model, $config = array()) {
        parent::setup($model, $config);
        $this->model = $model;
    }

    /**
     * Basic check for file upload data. Check if is_uploaded_file
     *
     * @param string $filename path to file
     * @return boolean true if is an uploaded file.
     * @author Joaquin Windmuller
     */
    protected final function _isUploadedFile($data, $required) {
        // Check for Basic PHP file errors.
        if ($data['error'] !== 0) {
            return false;
        }
        // Finally, use PHP's own file validation method.
        return is_uploaded_file($data['tmp_name']);
    }

    /**
     * Moves the uploaded files to its final destination.
     *
     * @param string $field_name name of the field
     * @param string $ext extension
     * @return boolean TRUE on success
     * @author Joaquin Windmuller
     */
    protected final function _upload($field_name, $ext, $saveAs = '') {
        $data = $this->model->data[$this->model->alias];
        $guide = @$data['guide'];

        $upload_dir = $this->_uploadDir($guide);

        if (empty($saveAs)) {
            $saveAs = $field_name;
        }

        $files = $this->_fileExtists($saveAs);
        foreach ($files as $file) {
            $file = new File($upload_dir . DS . $file);
            $file->delete();
        }

        $tmp_name = $data[$field_name]['tmp_name'];
        $upload_file_to = $upload_dir . $saveAs . '.' . $ext;
        return is_dir($upload_dir) && file_exists($upload_dir) && move_uploaded_file($tmp_name, $upload_file_to);
    }

    protected final function _fileExtists($fileName) {
        $data = $this->model->data[$this->model->alias];
        $guide = $data['guide'];
        $uploadPath = $this->_uploadDir($guide);
        $folder = new Folder($uploadPath, true);
        return $folder->find("{$fileName}.*");
    }

    protected final function _uploadDir($guide) {
        $upload_dir = WWW_ROOT . 'files' . DS . $guide;
        if ($this->subfolderName != '') {
            $upload_dir .= DS . $this->subfolderName;
        }
        return $upload_dir . DS;
    }
}
<?php
class JSONFileManager {
    private $file_path;

    public $file_name;

    public function __construct($base_directory,$file_name) {
        $this->file_name = $file_name;
        $this->file_path = rtrim($base_directory)  . DIRECTORY_SEPARATOR . $file_name;
    }

    public function saveDataIfNotExists($data) {
        if (!file_exists($this->file_path)) {
            $this->createEmptyFile();
        }

        $existing_data = $this->readData();
        if (empty($existing_data)) {
            $this->writeData($data);
            return true;
        }

        return false;
    }

    private function createEmptyFile() {
        $handle = fopen($this->file_path, 'w');
        fclose($handle);
    }

    private function readData() {
        $data = file_get_contents($this->file_path);
        return json_decode($data, true);
    }

    private function writeData($data) {
        $json_data = json_encode($data, JSON_PRETTY_PRINT);
        file_put_contents($this->file_path, $json_data);
    }
}
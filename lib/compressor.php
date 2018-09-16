<?php

class Compressor {

    protected $_sourceDir;
    protected $_destinationDir;
    protected $_quality = 55;
    protected $_imgName = '';

    public function __construct($sourceDir, $destinationDir) {
        $this->_sourceDir = $sourceDir;
        $this->_destinationDir = $destinationDir;
    }

    public function imageCompressor() {
        $images = glob($this->_sourceDir . "*");
        foreach ($images as $key => $image) {
            $responce = $this->filterImage($image);
            echo $key + 1 . "  " . $this->_imgName . "       " . $responce . "\n";
        }
    }

    public function filterImage($imageSrc) {
        $info = getimagesize($imageSrc);	
        $this->_imgName = str_replace($this->_sourceDir, '', $imageSrc);
        $destination = $this->_destinationDir . $this->_imgName;
        if ($info[0] < 400 || $info[1] < 400) {
            $file = fopen($imageSrc, 'r');
            file_put_contents($destination, $file);
            return "Too small for compressed";
        }
        $quality = $this->_quality;
        $case = $info['mime'];
        switch ($case) {
            case 'image/jpeg':
                $image = imagecreatefromjpeg($imageSrc);
                $isDone = imagejpeg($image, $destination, $this->_quality);
                break;
            case 'image/png':
                $image = imagecreatefrompng($imageSrc);
                $isDone = imagepng($image, $destination, $this->_quality / 10);
                break;
            default :
                return "Unsupported Image Format";
        }
        if ($isDone) {
            return "successfully Compressed";
        }
        return "Failed";
    }

}

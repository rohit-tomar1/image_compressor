<?php

class Thumbnail {

    protected $_sourceDir;
    protected $_destinationDir;
    protected $_imgName = '';
    protected $_width = 400;
    protected $_height = 280;
    protected $_quality = 60;

    public function __construct($sourceDir, $destinationDir) {
        $this->_sourceDir = $sourceDir;
        $this->_destinationDir = $destinationDir;
    }

    public function thumbGenerater() {
        $images = glob($this->_sourceDir . "*");
        foreach ($images as $key => $image) {
            $responce = $this->thumbCreater($image);
            echo $key + 1 . "  " . $this->_imgName . "       " . $responce . "\n";
        }
    }

    private function thumbCreater($imageSrc) {
        $info = getimagesize($imageSrc);
        $this->_imgName = str_replace($this->_sourceDir, '', $imageSrc);
        $destination = $this->_destinationDir . $this->_imgName;
        if ($info[0] < 200 || $info[1] < 100) {
            $file = fopen($imageSrc, 'r');
            file_put_contents($destination, $file);
            return "Too small for thumb";
        }
        $case = $info['mime'];
        switch ($case) {
            case 'image/jpeg':
                $image = imagecreatefromjpeg($imageSrc);
                $thumb = $this->fileterImage($image);
                $isDone = imagejpeg($thumb, $destination, $this->_quality);
                break;
            case 'image/png':
                $image = imagecreatefrompng($imageSrc);
                $thumb = $this->fileterImage($image);
                $isDone = imagepng($image, $destination, $this->_quality / 10);
	 	break;
            default :
                return "Unsupported Image Format";
        }
        if ($isDone) {
            return "thumbnail created successfully";
        }
        return "Failed";
    }

    private function fileterImage($image) {
        $thumb_width = $this->_width;
        $thumb_height = $this->_height;
        $width = imagesx($image);
        $height = imagesy($image);
        $original_aspect = $width / $height;
        $thumb_aspect = $thumb_width / $thumb_height;
        if ($original_aspect >= $thumb_aspect) {
            $new_height = $thumb_height;
            $new_width = $width / ($height / $thumb_height);
        } else {
            $new_width = $thumb_width;
            $new_height = $height / ($width / $thumb_width);
        }
        $thumb = imagecreatetruecolor($thumb_width, $thumb_height);
        imagecopyresampled($thumb, $image, 0 - ($new_width - $thumb_width) / 2, 0 - ($new_height - $thumb_height) / 2, 0, 0, $new_width, $new_height, $width, $height);
        return $thumb;
    }

}

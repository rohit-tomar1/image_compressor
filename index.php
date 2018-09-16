<?php

require './lib/compressor.php';
require './lib/thumbnail.php';

$sourceDir = './Normal_Image/';
$destinationDir = './Compressed_Image/';
$thumbDir = './Thumbnail/';

$compressObj = new Compressor($sourceDir, $destinationDir);
$compressObj->imageCompressor();

$thumbObj = new Thumbnail($sourceDir, $thumbDir);
$thumbObj->thumbGenerater();









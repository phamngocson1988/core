<?php
namespace common\components\filesystem;

interface ImageSystemInterface
{
	public function saveImage($file, $fileModel);
	public function saveThumbnail($fileModel, $thumbnail) ;
	public function get($fileModel, $thumbnail = null);
}
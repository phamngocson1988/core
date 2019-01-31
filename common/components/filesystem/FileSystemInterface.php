<?php
namespace common\components\filesystem;

interface FileSystemInterface
{
	public function saveImage($file, $fileModel);
	public function saveThumbnail($fileModel, $thumbnail) ;
	public function get($fileModel, $thumbnail = null);
}
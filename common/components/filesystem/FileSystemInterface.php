<?php
namespace common\components\filesystem;

interface FileSystemInterface
{
	public function save($file, $fileModel);
	public function getUrl($fileModel);
	public function getPath($fileModel);
}
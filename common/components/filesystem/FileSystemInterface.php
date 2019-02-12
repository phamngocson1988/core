<?php
namespace common\components\filesystem;

interface FileSystemInterface
{
	public function save($file, $fileModel);
	public function get($fileModel);
}
<?php

class UploadManager  {

	private $uploads = array();

	public function __construct($move2Dir) {
	
		if(strpos(strrev($move2Dir),'/') !== 0) $move2Dir = $move2Dir.'/'; 
		if(!is_dir($move2Dir)) die('Destination directory for upload does not exist.');
		
		foreach($_FILES as $filekey => $fileval) {
			$f = new UploadedFile($fileval);
			array_push($this->uploads,$f);

			$f->rename(date('YmdHis').'_'.$f->getFileName());
			$f->move($move2Dir);
		}
	}
	
	public function hasFiles() {
		if(count($this->uploads) > 0) return true;
		return false;
	}
	
	public function pop() {
		if($this->hasFiles()) return array_pop($this->uploads);
		return null;
	}
}

class UploadedFile {
	private $tempPath = false;
	private $fileName = false;
	private $filePath = false;
	private $originalFileName = false;
	private $mimetype = false;
	
	private $inTemp = true;
	
	public function __construct($filearray) {
		if(!empty($filearray['name'])) {
			$file = $filearray;
			$this->originalFileName = $file['name'];
			$this->mimetype = $file['type'];
			$this->tempPath = $file['tmp_name'];	
			
			$this->filePath = $this->tempPath;
			$this->fileName = $this->originalFileName;
		}
	}
	
	public function rename($name) {
		if(file_exists($this->filePath)) {
			rename($this->filePath,$this->getDir().$name) or die('Can\'t rename file!');
			$this->fileName = $name;
			$this->filePath = $this->getDir().$name;
		} else {
			die('File to rename does not exist.');
		}
	}
	
	public function move($dir) {
		if(strpos(strrev($dir),'/') !== 0) $dir = $dir.'/'; 
		
		if(file_exists($this->filePath) && is_dir($dir)) {
			rename($this->filePath, $dir.$this->fileName) or die('Can\'t move file!');
			$this->filePath = $dir.$this->fileName;
		} else {
			die('File to move does not exist.');
		}
	
		if($this->inTemp && file_exists($this->tempPath)) {
			unlink($this->tempPath);
		}		
		$this->inTemp = false;
	}
	
	public function delete() {
		if(file_exists($this->filePath)) unlink($this->filePath) or die('Unable to unlink file.');
	}
	
	private function getDir() {
		$e = explode('/',$this->filePath);
		array_pop($e);
		$e = implode('/',$e);
		$e = $e.'/';
		return $e;
	}
	
	public function getOrigName() { return $this->originalFileName; }
	public function getFileName() { return $this->fileName; }
	public function getFilePath() { return $this->filePath; }
	public function getFileDirectory() { return $this->getDir(); }
	public function getMimeType() { return $this->mimeType; }
}


?>
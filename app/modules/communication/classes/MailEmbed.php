<?php
App::uses("FileUtility", "modules/coreutils/utility");

class MailEmbed {
	public $path;
	public $cid;
	public $name;
	public $encoding= "base64";
	public $mimetype;

	function __construct($path, $cid, $mimetype, $name= null) {
		$this->path= $path;
		$this->cid= $cid;
		$this->mimetype= $mimetype;
		if (empty($name)) {
			$this->name= FileUtility::getNameByPath($path);
		} else {
			$this->name= $name;
		}
	}
}
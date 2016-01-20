<?php

class Bom{
	public $auto_rewrite = 0;
	public $detect_dir;
	public $bom_files = array();

	public function __construct($detect_dir = __DIR__, $auto_rewrite = 0){
		$this->detect_dir = $detect_dir;
		$this->auto_rewrite = $auto_rewrite;
	}

	public function check_dir($detect_dir=''){
		if(empty($detect_dir)){
			$detect_dir = $this->detect_dir;
		}

		if($dh = opendir($detect_dir)) {
			while (($file = readdir($dh)) !== false) {
				if($file != '.' && $file != '..'){
					$check_path = realpath($detect_dir."/".$file);
					if(!is_dir($check_path)) {
						$bom_res = $this->is_bom_file($check_path);
						empty($bom_res) OR $this->bom_files[] = $check_path;
					}else{
						$dirname = $detect_dir."/".$file;
						$this->check_dir($dirname);
					}
				}
			}
			closedir($dh);
		}
	}

	public function is_bom_file($filename) {
		$contents = file_get_contents($filename);
		$charset[1] = substr($contents, 0, 1);
		$charset[2] = substr($contents, 1, 1);
		$charset[3] = substr($contents, 2, 1);
		if (ord($charset[1]) == 239 && ord($charset[2]) == 187 && ord($charset[3]) == 191) {
			return TRUE;
		}
		return False;
	}

	public function remove_file_with_bom($filename){
		if($this->is_bom_file($filename)){
			$contents = file_get_contents($filename);
			$rest = substr($contents, 3);
			$this->rewrite($filename, $rest);
		} 
	}

	public function rewrite($filename, $data) {
		$filenum = fopen($filename, "w");
		flock($filenum, LOCK_EX);
		fwrite($filenum, $data);
		fclose($filenum);
		return TRUE;
	}

	public function get_bom_files(){
		$this->check_dir();
		return $this->bom_files;
	}

	public function auto_remove_file_bom(){
		foreach ($this->bom_files as $bom_file) {
			if($this->remove_file_with_bom($bom_file)){
				return 'success';
			}
		}
	}

	public function start_handle_files(){
		$bom_files = $this->get_bom_files();
		$ret = array('bom_files' => $bom_files);

		empty($this->auto_rewrite) OR $ret['remove_ret'] = $this->auto_remove_file_bom();
		return json_encode($ret);
	}
}

//使用示例：
$bom_obj = new Bom(__DIR__, 0);
echo $bom_obj->start_handle_files();

?>

<?php

function get_all_files($dir_path) {
	static $all_file_path = array();

	if(!empty($dir_path)) {
		$tmp_dirs = scandir($dir_path); //print_r($tmp_dirs);
		foreach($tmp_dirs as $tmp_dir){
			if($tmp_dir != '.' && $tmp_dir != '..') {
				$tmp_file_path = $dir_path."/".$tmp_dir; //echo $tmp_file_path."<br>";
				if(is_dir($tmp_file_path)) {
					get_all_files($tmp_file_path);
				}else {
					$all_file_path[] = $tmp_file_path;
				}
			}
		}

		return $all_file_path;
	}
}
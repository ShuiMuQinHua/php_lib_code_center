<?php

$url = "http://www.baidu.com/s?wd=php";

#方法一：file_get_contents
#用file_get_contents或者fopen、file、readfile等函数读取url的时候，会创建一个名 $http_response_header的变量来保存http响应的报头.

function common_get_headers_by_fileget($url){
	file_get_contents($url);
	return $http_response_header;//调用file_get_contents是自动生成此变量
}

#方法二： curl函数
function common_get_headers_by_curl($sUrl){
	$oCurl = curl_init();

	$header[] = "Content-type: application/x-www-form-urlencoded";
	$user_agent = "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/33.0.1750.146 Safari/537.36";

	curl_setopt($oCurl, CURLOPT_URL, $sUrl);
	curl_setopt($oCurl, CURLOPT_HTTPHEADER,$header);

	// 返回 response_header, 该选项非常重要,如果不为 true, 只会获得响应的正文
	curl_setopt($oCurl, CURLOPT_HEADER, true);
	// 是否不需要响应的正文,为了节省带宽及时间,在只需要响应头的情况下可以不要正文
	curl_setopt($oCurl, CURLOPT_NOBODY, true);


	//curl_setopt($oCurl, CURLOPT_USERAGENT,$user_agent);
	curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1 );
	// 不用 POST 方式请求, 意思就是通过 GET 请求
	curl_setopt($oCurl, CURLOPT_POST, false);

	$sContent = curl_exec($oCurl);
	// 获得响应结果里的：头大小
	$headerSize = curl_getinfo($oCurl, CURLINFO_HEADER_SIZE);
	// 根据头大小去获取头信息内容
	$header = substr($sContent, 0, $headerSize);
	    
	curl_close($oCurl);

	return $header;
}

#方法三：使用get_headers
function common_get_headers_by_getheaders($url){
	$response_headerinfo = get_headers($url);
	return $response_headerinfo;
}

#方法四： 
     
 $fp = fopen($url, 'r');      
 print_r(stream_get_meta_data($fp));      
 fclose($fp);  

?>
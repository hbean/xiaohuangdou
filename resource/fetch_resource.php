<?php
	chdir(__DIR__);
	//$file = "./common_8d7446f.css";
	$file = "./home_31d92cf.css";
	preg_match_all("/url\((\/static\/.*?)\)/i",file_get_contents($file),$match);
	//print_r($match[1]);

	foreach($match[1] as $file_name){
		$origin_repo_url = "http://store.baidu.com";
		$res_url = $origin_repo_url.$file_name;
		getImage($res_url,'');
	}

/*
*功能：php完美实现下载远程图片保存到本地
*参数：文件url,保存文件目录,保存文件名称，使用的下载方式
*当保存文件名称为空时则使用远程文件原来的名称
*/
function getImage($url,$save_dir='',$remain_path=1,$type=0){
    if(trim($url)==''){
		return array('file_name'=>'','save_path'=>'','error'=>1);
	}

	if(trim($save_dir)==''){
		$save_dir = './';
	}else{
		$save_dir = rtrim($save_dir,'/').'/';
	}



	$res = parse_url($url);
	$filename = basename($res['path']);
	if($remain_path){
		$save_dir .= ltrim(dirname($res['path']),'/').'/';
	}

	echo $url."\n";
	echo $filename."\n";
	echo $save_dir."\n";

	//创建保存目录
	if(!file_exists($save_dir)&&!mkdir($save_dir,0777,true)){
		return array('file_name'=>'','save_path'=>'','error'=>5);
	}
    //获取远程文件所采用的方法 
    if($type){
		$ch=curl_init();
		$timeout=5;
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
		$img=curl_exec($ch);
		curl_close($ch);
    }else{
	    ob_start(); 
	    readfile($url);
	    $img=ob_get_contents(); 
	    ob_end_clean(); 
    }
    //$size=strlen($img);
    //文件大小 
    $fp2=@fopen($save_dir.$filename,'a');
    fwrite($fp2,$img);
    fclose($fp2);
	unset($img,$url);
    return array('file_name'=>$filename,'save_path'=>$save_dir.$filename,'error'=>0);
}
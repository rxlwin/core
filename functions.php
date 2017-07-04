<?php
/**
 * 按照原格式打印输出
 * @param $var
 */
function p($var){
    echo "<pre style='padding: 20px;background: #EEE;'>".print_r($var,true)."</pre>";
}

/**
 * 自动生成路径函数
 * @param $var
 * @return string
 */
function u($var,$str=null){
    //home/entry/index
    //entry/index
    //index
    //1.定义路径为空 2.因为如果传来参数有问题,我们就直接转向主页
    $path="";
    //1.将传来参数先整理一下 2.这是我的强迫症
    $var = str_replace("\\","/",$var);
    //1.使用/分割字符串成数组 2.可以取出每个元素
    $arr = explode("/",$var);
    //1.利用数组的不同长度组合不同的路径 2.利用了默认的规范,具体如下
    //首先,每次的参数传递肯定是要传方法的
    //其次,如果控制器不同,就传递控制器和方法
    //最后,如果模块也需要变化,就会把三个参数全部传进来
    //这是基于,我们已经给当前的模块,控制器,方法 都定义了常量.
    switch (count($arr)){
        //1.当参数只有一个时 2.说明只传了方法
        case 1:
            //1.组合相应路径 2.为了后面使用返回完整路径
            $path = "?s=".MODULE."/".CONTROLLER."/".$arr[0];
            break;
        //1.当参数只有两个时 2.说明传了控制器和方法
        case 2:
            //1.组合相应路径 2.为了后面使用返回完整路径
            $path = "?s=".MODULE."/".$arr[0]."/".$arr[1];
            break;
        //1.当参数只有三个时 2.说明传了模块.控制器和方法
        case 3:
            //1.组合相应路径 2.为了后面使用返回完整路径
            $path = "?s=".$arr[0]."/".$arr[1]."/".$arr[2];
            break;
    }
    if(!is_null($str)){
        $path .= "&{$str}";
    }
    //1.返回完整路径 2.以供调用的代码使用.
    return __ROOT__.$path;
}

/**
 * 获取配置项
 * 例 v("config.CODE_LEN"); 意思是获取confing文件中的LODE_LEN配置项
 *
 * @param $name
 */
function v($name){
    //1.用.分割字符串为数组 2.前面的是文件名,后面的配置项键名
    $arr=explode(".",$name);
    //1.组合配置项路径 2.需要从文件中读取配置项,就要先载入文件,载入文件就需要文件路径
    $file="../system/config/".$arr[0].".php";
    //1.载入文件 2.为获得所有配置项
    $code=include $file;
    //1.如果需要读取的配置项存在,就读取配置项,如果不存在就给个null值 2.这样就不会报错
    return isset($code[$arr[1]]) ? $code[$arr[1]] : null;

}
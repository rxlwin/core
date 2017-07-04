<?php
namespace rxlwin\core;//1.声明类的命名空间 2.由composer规范,以实现自动加载,自由调用

/**
 * 框架启动类,框架总入口类
 * Class Boot
 * @package myself\core
 */
class Boot{
    /**
     * 框架入口方法,所有框架的加载与执行,都通过本方法调用执行
     */
    public static function run(){
        //1.错误处理 2.使用第三方类库加载
        self::handelError();

        //1.设置常量 2.为后续需要的常量作好准备,并且实现了预加载
        self::init();

        //1.静态调用apprun 2.实现用户应用类的加载与调用执行
        self::apprun();
    }

    /**
     * 错误处理
     * 加载第三方错误处理类
     * 具体机制我不懂
     * https://packagist.org/packages/filp/whoops
     * 以下封装代码来自于插件说明,我们自己做了一个简单的封装,并在run方法中进行了调用.
     */
    static private function handelError() {
        $whoops = new \Whoops\Run;
        $whoops->pushHandler( new \Whoops\Handler\PrettyPageHandler );
        $whoops->register();

    }


    /**
     * 1.设置常量 2.为后续需要的常量作好准备,并且实现了预加载
     */
    private static function init(){
        //1.开启session 2.先判断一下是否已经开启.
        session_id() || session_start();
        //1.设备默认时区 2.因为我们在东八区,如果不设置,将来使用日期函数时可能会报错.而且时间也不准
        date_default_timezone_set("PRC");
        //1.设置IS_POST常量 2.用来后面判断是否POST提交
        define("IS_POST",$_SERVER["REQUEST_METHOD"]=="POST" ? true : false);
        //1.设置入口网址 2.以后要经常用到
        define("__ROOT__" , "http://".$_SERVER["HTTP_HOST"].$_SERVER["SCRIPT_NAME"]);
    }

    /**
     * 1.应用程序的加载与调用 2.通过GET参数来调用不同的应用类控制器来显示不同的页面
     */
    private static function apprun(){
        //1.如果$GET['s']参数存在 2.就是用户有指定的页面要显示
        //我们的用意是通过用户在地址栏传递"s=home/entry/index" 这样的参数来调用不同的控制器的不同方法,以实现显示不同页面的目的
        if(isset($_GET["s"])){
            //1.统一用户分割符 2.有时可能会出现些小误会,为了不影响后续程序执行,先在这里统一一下.
            $s=str_replace("\\","/",$_GET['s']);
            //1.利用/符分割字符串,将其变为数组
            $s=explode("/",$s);
        }
        //因为我们规范是按照 "模块->控制器->方法" 的顺序进行参数书写的,所以我们也可以按照这个顺序获得相应的参数.
        //1.得到模块参数 2.再加一次判断,以防报错
        $m=isset($s[0]) ? strtolower($s[0]) : "home";
        //1.得到控制器参数 2.再加一次判断,以防报错
        $c=isset($s[1]) ? strtolower($s[1]) : "entry";
        //1.得到方法参数 2.再加一次判断,以防报错
        $a=isset($s[2]) ? strtolower($s[2]) : "index";
        //1.组合文件路径 2.后面我还要再检查一下该文件路径是不是存在,因为可能用户会把GET参数输错
        $path="../app/{$m}/controller/".ucfirst($c).".php";
        //1.判断上面组合的路径是否存在 2.再判断用户在地址栏里输入的参数是不是正确
        if(!is_file($path)){
            //1.如果路径不存在,说明用户输入的参数有问题,那我们就给控制器赋个默认值,或者应该报错
            $m="home";
            $c="entry";
            $a="index";
        }
        //1.定义常量 2.我们在myself->view->Base.php中需要加载控制器对应的模板,届时需要利用这些参数组合出模板路径
        define("MODULE",$m);
        define("CONTROLLER",$c);
        define("ACTION",$a);
        //1.组合控制器带命名空间的完整路径 2.接下来需要实例化控制器调用方法
        $controller="\app\\".$m."\controller\\".ucfirst($c);
        //1.实例化控制器用调用$a方法 2.用来显示该方法所能呈现的网页
        echo call_user_func_array([new $controller,$a],[]);
        //注意, 这里使用了echo,为什么呢,其实是为了触发Base里的__toString方法,因为此方法必须在echo一个对象时才会被触发.

    }
}
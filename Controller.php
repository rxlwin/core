<?php
namespace rxlwin\core;//1.声明类的命名空间 2.由composer规范,以实现自动加载,自由调用

/**
 * 控制器基类,应该类控制器可以继承此类
 * Class Controller
 * @package myself\core
 */
class Controller{
    public $url='window.history.go(-1);';
    /**
     * 显示消息信息
     * @param $msg
     */
    protected function message($msg){
        //1.载入模板 2.因为是公用模板,所以存在public下面
        include "./view/message.php";

        //注意,这里不设置返回对象,不能让从这里还能链式操作,这样后续参数传递就没有意义了.
        exit;
    }

    /**
     * 设置跳转路径
     * @param null $url
     * @return $this
     */
    protected function setredirect($url=null){
        //1.如果不传,就走默认跳转,默认跳转是返回上一页. 2.这样的话,即使不设置跳转,也可以有跳转,不会一直在消息页面不动.
        if(!is_null($url)){
            //1.如果有$url传来,就将此$url设置成跳转路径 2.最后调用页面的方法不本方法,所以需要升级成为全局变量
            $this->url="window.location.href='{$url}'";
        }
        //1.返回本对象 2.可以实现链式操作.
        return $this;
    }
}
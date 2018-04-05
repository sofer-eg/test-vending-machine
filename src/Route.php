<?php
namespace App;

class Route {

    use Singleton;

    private $model;
    private $view;

    private static $defaultController = 'default';
    private static $defaultAction = 'index';

    private function convert($part) {
        return str_replace(' ', '', mb_ucwords(str_replace('-', ' ', $part)));
    }

    private function getControllerName($name) {
        return $this->convert($name) . 'Controller';
    }

    private function getActionName($name) {
        return 'action' . $this->convert($name);
    }

    public function execute($route) {
        if(!is_array($route)) {
            $route = func_get_args();
        }
        $c = '\Controller\\' . $this->getControllerName($route[0]);
        $Controller = new $c();
        $m = $this->getActionName($route[1]);
        if(method_exists($Controller, $m)) {
            $Controller->$m($this->view, $this->model);
        } else {
            $this->redirect('default', 'not-exist');
        }
    }

    public function exist($route) {
        if(!is_array($route)) {
            $route = func_get_args();
        }
        return file_exists('framework/controller/' . $this->getControllerName($route[0]) . '.php');
    }

    public function getRoute() {
        $route = [self::$defaultController, self::$defaultAction];
        if(!empty($_GET['c'])) {
            $route[0] = $_GET['c'];
        }
        if(!empty($_GET['a'])) {
            $route[1] = $_GET['a'];
        }
        return $route;
    }

    public function process(View $view, Model $model) {
        $this->model = $model;
        $this->view = $view;
        $route = $this->getRoute();
        if($this->exist($route)) {
            $this->execute($route);
        } else {
            $this->execute('default', 'not-exist');
        }
    }

    public static function redirect($url) {
        $tmp = func_get_args();
        if(!is_array($url)) {
            $url = $tmp;
        }
        header('Location: ' . self::url($url));
        die;
    }

    public static function url($url) {
        $tmp = func_get_args();
        if(!is_array($url)) {
            $url = $tmp;
        }
        $get = [];
        if(is_array($url)) {
            $tmp = $url;
            $url = '';
            if(count($tmp) == 1) {
                if(!empty($tmp[0])) {
                    $url = '?c=' . $tmp[0];
                }
            } elseif(count($tmp) == 2 && is_array($tmp[1])) {
                if(!empty($tmp[0])) {
                    $url = '?c=' . $tmp[0];
                }
                $get = $tmp[1];
            } else {
                $url = '?c='.$tmp[0].'&a='.$tmp[1];
                if(isset($tmp[2])) {
                    $get = $tmp[2];
                }
            }
            if(!empty($get)) {
                $url .= strpos($url, '?') === false ? '?' : '&';
                if(is_array($get)) {
                    array_walk($get, function(&$val, $key) {
                        $val = $key . '=' . $val;
                    });
                    $url .= implode('&',$get);
                } else {
                    $url .= $get;
                }
            }
        }
        return Config::get('baseUrl') . $url;
    }

}
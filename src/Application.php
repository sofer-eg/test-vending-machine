<?php
namespace App;

class Application {

    private $route;
    private $view;
    private $model;

    public function __construct(Route $route, View $view, Model $model) {
        $this->route = $route;
        $this->view = $view;
        $this->model = $model;
    }

    public function run() {
        $this->route->process($this->view, $this->model);
        $this->view->display();
    }

}
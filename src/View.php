<?php
namespace App;

class View {

    use Singleton;

    private $layout = 'main';

    private $positions = [
        'title' => '',
        'content' => '',
    ];

    public function __construct() {
        $this->layout = 'main';
    }

    public function fetch($path, $data = array()) {
        if(file_exists('framework/view/' . $path . '.php')) {
            if(!empty($data)) {
                extract($data);
            }
            ob_start();
            include('framework/view/' . $path . '.php');
            $return = ob_get_contents();
            ob_end_clean();
            return $return;
        }
        return '';
    }

    public function __set($name, $value) {
        if(isset($this->positions[$name])) {
            $this->positions[$name] .= $value;
        }
    }

    public function display() {
        echo $this->fetch('layout/' . $this->layout, $this->positions);
    }

    public function setLayout($layout) {
        $this->layout = $layout;
    }

    public function setAlert($message, $title = 'Ошибка') {
        if(isset($_SESSION['message']['text'])) {
            $_SESSION['message']['text'] .= '<br>'.$message;
        } else {
            $_SESSION['message']['text'] = $message;
        }
        $_SESSION['message']['title'] = $title;
    }

}

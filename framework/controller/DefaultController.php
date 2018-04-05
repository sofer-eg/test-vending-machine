<?php
namespace Controller;

use \App\Config as Config;
use App\Database;
use App\Model;
use App\Request;
use App\Route;
use App\View;

class DefaultController extends \App\Controller {

    public function actionIndex(View $view, Model $model) {
        $user = $model->toArray('SELECT * FROM `user` WHERE `id` = 1', '', '', true);
        $products = $model->toArray('SELECT * FROM `product`');
        $machine = $model->toArray('SELECT * FROM `machine`');
        $view->title = Config::get('siteName');
        $view->content = $view->fetch('default/default', ['user' => $user, 'products' => $products, 'machine' => $machine]);
    }

    public function actionNotExist(View $view) {
        $view->setLayout('empty');
        $view->title = Config::get('siteName');
    }

    public function actionBuy(View $view, Model $model) {
        $productId = Request::get('product');
        if(!is_null($productId)) {
            try {
                Database::$con->query('START TRANSACTION');
                $user = $model->toArray('SELECT * FROM `user` WHERE `id` = 1 FOR UPDATE', '', '', true);
                $product = $model->toArray('SELECT * FROM `product` WHERE `id` = ' . $productId, '', '', true);

                if(empty($product)) {
                    $view->setAlert('Товар не найден');
                    throw new \Exception("No product with this id!");
                }

                if($product['amount'] == 0) {
                    $view->setAlert('Товар кончился');
                    throw new \Exception("Empty product slot!");
                }

                if($product['cost'] > $user['reserve']) {
                    $view->setAlert('Внесено недостаточно средств');
                    throw new \Exception("Not enough money!");
                }

                Database::$con->query('UPDATE `user` SET `reserve` = `reserve` - ' . $product['cost'] . ' WHERE `id` = 1');
                Database::$con->query('UPDATE `product` SET `amount` = `amount` - 1 WHERE `id` = ' . $productId);
                Database::$con->query('COMMIT');
                $view->setAlert('Спасибо за покупку!', 'Спасибо');
            } catch (\Exception $e) {
                Database::$con->query('ROLLBACK');
            }
        }
        Route::redirect('');
    }

    public function actionInsertCoin(View $view, Model $model) {
        $coin = Request::get('coin');
        if(!is_null($coin)) {
            try {
                Database::$con->query('START TRANSACTION');
                $user = $model->toArray('SELECT * FROM `user` WHERE `id` = 1 FOR UPDATE', '', '', true);
                if($user['coin_' . $coin] > 0) {
                    Database::$con->query('UPDATE `user` SET `coin_' . $coin . '` = `coin_' . $coin . '` - 1, reserve = reserve + ' . $coin . ' WHERE `id` = 1');
                    Database::$con->query('UPDATE `machine` SET `count` = `count` + 1 WHERE `coin` = ' . $coin);
                } else {
                    $view->setAlert('Недостаточно средств');
                    throw new \Exception("No money!");
                }
                Database::$con->query('COMMIT');
            } catch (\Exception $e) {
                Database::$con->query('ROLLBACK');
            }
        }
        Route::redirect('');
    }

    public function actionGetChange(View $view, Model $model) {
        try {
            Database::$con->query('START TRANSACTION');
            $user = $model->toArray('SELECT `reserve` FROM `user` WHERE `id` = 1 FOR UPDATE', '', '', true);
            $machine = $model->toArray('SELECT * FROM `machine` ORDER BY `coin` DESC FOR UPDATE ', 'coin');
            $coins = $this->getChange($user['reserve'], $machine);
            if($coins !== false) {
                $queryIn = [];
                $queryOut = [];

                foreach ($coins as $coin => $count) {
                    if($count != 0) {
                        $queryIn[] = '`coin_' . $coin . '` = `coin_' . $coin . '` + ' . $count;
                    }
                    $queryOut[] = 'WHEN `coin` = ' . $coin . ' THEN ' . $count;
                }

                Database::$con->query('UPDATE `user` SET reserve = 0, ' . implode(', ', $queryIn) . ' WHERE `id` = 1');
                Database::$con->query('UPDATE `machine` SET `count` = `count` - (CASE ' . implode(' ', $queryOut) . ' END)');
            } else {
                $view->setAlert('Недостаточно средств для сдачи');
                throw new \Exception("No money for change!");
            }
            Database::$con->query('COMMIT');
        } catch (\Exception $e) {
            Database::$con->query('ROLLBACK');
        }
        Route::redirect('');
    }

    private function getChange($amount, $coins) {
        $change = [
            10 => 0,
            5 => 0,
            2 => 0,
            1 => 0,
        ];
        foreach($change as $coin => $c) {
            if($amount >= $coin && $coins[$coin] > 0) {
                $count = floor($amount / $coin);
                $mod = $amount % $coin;
                if($count > $coins[$coin]) {
                    $mod += ($count - $coins[$coin]) * $coin;
                    $count = $coins[$coin];
                }
                $amount = $mod;
                $change[$coin] += $count;
            }
        }
        return $amount == 0 ? $change : false;
    }
}
<div class="row mt-3">
    <div class="col">
        <h3>Кошелёк пользователя</h3>
    </div>
</div>
<div class="row mt-3">
    <?php foreach ($user as $key => $value) {
        $data = explode('_', $key);
        if(count($data) == 2 && $data[0] == 'coin') {
    ?>
            <div class="col">
                <div class="card">
                    <div class="card-body text-center">
                        <h2 class="card-title"><?= $data[1] ?> р.</h2>
                        <a href="<?= \App\Route::url('default', 'insert-coin', ['coin' => $data[1]]) ?>" class="btn btn-primary btn-block">Внести</a>
                    </div>
                    <div class="card-footer text-muted">
                        Осталось: <?= $value ?> шт.
                    </div>
                </div>
            </div>
    <?php
        }
    }
    ?>
</div>
<hr>
<div class="row mt-5">
    <div class="col">
        <h3>Автомат <span class="float-right">Внесенная сумма: <?= $user['reserve'] ?> р. <a href="<?= \App\Route::url('default', 'get-change') ?>" class="btn btn-success">Сдача</a></span></h3>
    </div>
</div>
<div class="row mt-3">
    <?php foreach ($products as $product) { ?>
        <div class="col">
            <div class="card">
                <div class="card-header text-center">
                    Цена: <?= $product['cost'] ?> р.
                </div>
                <div class="card-body text-center">
                    <h2 class="card-title"><?= $product['name'] ?></h2>
                    <a href="<?= \App\Route::url('default', 'buy', ['product' => $product['id']]) ?>" class="btn btn-success btn-block">Купить</a>
                </div>
                <div class="card-footer text-muted">
                    Осталось: <?= $product['amount'] ?> шт.
                </div>
            </div>
        </div>
    <?php
    }
    ?>
</div>
<hr>
<div class="row mt-3">
    <div class="col">
        <h5>Сейчас в автомате</h5>
    </div>
</div>
<div class="row mt-3">
    <?php foreach ($machine as $coin) { ?>
        <div class="col">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title"><?= $coin['coin'] ?> р.</h5>
                </div>
                <div class="card-footer text-muted">
                    Осталось: <?= $coin['count'] ?> шт.
                </div>
            </div>
        </div>
        <?php
    }
    ?>
</div>
<?php
use \FW\Cache\Cache as Cache;

if(Cache::beginCache('lucky_block', array('safe'=>true,'compress'=>true, 'expire'=>43200))) { ?>
    <div class="main">
        <h2>Наши счастливчики сегодня<br><?php Cache::noCache('<?php echo date("m.d.y"); ?>');?></h2>
        <ol class="rounded-list">
            <?php
            foreach ($data as $v) {
                echo '<li><a href="#">'.hc($v, true).'</a></li>';
            }
            ?>
        </ol>
    </div>
    <?php Cache::endCache(); } ?>

<?php
/*
    Проблема: переменную $data не видно.
    Причина: eval выполняется внутри функции beginCache но внутрь функции мы не передали $data
    Решение: скорее всего надо доп.параметр передавать внутрь beginCache, а так же в коде прописать
             получение этих самых переменных для использования внутри noCache блока

    $data = 'test';

    if(Cache::beginCache('lucky_block', array('safe'=>true,'compress'=>true, 'expire'=>43200))) {
        Cache::noCache('<?php echo $data;?>');
        Cache::endCache();
    }
  */
?>
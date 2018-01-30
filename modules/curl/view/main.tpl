<div class="main">
    <a href="/test?act=go">GO</a>
    <?php
    if (isset($x) && $x !== false) { ?>
       <br> Комментарий успешно отправляет. Посмотри на свое творение
       <a href="http://todo.kh.ua/comments" target="_blank">тут</a>
       <script>
           // меняем адресную строку без перезагрузки
           history.pushState(null, null, 'test');
       </script>
    <?php
    }
    ?>
</div>
<main class="main">
    <a href="/trans_one/1">Грязное чтение (запустить первым)</a><br>
    <?php if (isset($row)) {wtf($row,1); echo '<br>';}?>
    <a href="/trans_one/2">Неповторяющееся чтение</a><br>
    <?php if (isset($row1)) {wtf($row1,1); echo '<br>';}?>
    <a href="/trans_one/3">Фантомное чтение</a><br>
    <?php if (isset($row2)) {wtf($row2,1); echo '<br>';}?>
    <hr>
    <a href="/trans_one">Сбростить</a>
</main>
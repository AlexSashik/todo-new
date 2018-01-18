<main class="main">
    <a href="/trans_two/1">Грязное чтение</a><br>
    <?php if (isset($row)) {wtf($row,1); echo '<br>';}?>
    <a href="/trans_two/2">Неповторяющееся чтение (запустить первым)</a><br>
    <?php if (isset($row1, $row2)) {wtf($row1,1); wtf($row2,1); echo '<br>';}?>
    <a href="/trans_two/3">Фантомное чтение (запустить первым)</a><br>
    <?php if (isset($row3, $row4)) {wtf($row3,1); wtf($row4,1); echo '<br>';}?>
    <hr>
    <a href="/trans_two">Сбростить</a>
</main>
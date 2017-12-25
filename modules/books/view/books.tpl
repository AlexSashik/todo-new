<div class="main">
    <h2>БИБЛИОТЕКА</h2>
    <div class="books_nav">
        <a href="/books" <?php if (!isset($author_search)) echo'class="book_nav_active"';?>>Все книги</a>
        <a href="/books/authors">Все авторы</a>
    </div>
    <?php
    if (isset($author_search)) {
        ?>
        <div class="author_info">
            Поиск по автору: <em><b><?php echo htmlspecialchars($author_search['name']);?></b></em>
            <img alt="" src="/skins/img/default/authors/100x100/<?php echo htmlspecialchars($author_search['img_name']); ?>">
        </div>
        <?php
    }
    if (isset($res, $how_total_pages, $data)) {
        foreach ($data as $v) {
            ?>
            <div class="books_list">
                <div class="for_img">
                    <img alt="" src="/skins/img/default/books/200x200/<?php echo htmlspecialchars($v['img_name']);?>">
                </div>
                <div class="book_description">
                    <p><strong>Название: </strong><?php echo htmlspecialchars($v['name']);?></p>
                    <br>
                    <p><strong>Автор: </strong><?php echo htmlspecialchars($v['auth']);?></p>
                    <br>
                    <p><strong>Год издания: </strong><?php echo htmlspecialchars($v['year']);?></p>
                    <br>
                    <p><strong>Описание: </strong><?php echo nl2br(htmlspecialchars($v['text']));?></p>
                    <br>
                </div>
            </div>
            <?php
        }
        ?>
        <div class="paginator">
            <?php
            if (isset($get_id_auth)) {
                echo Paginator::pages((int)$_GET['pagenumber'], $how_total_pages, '/books', $get_id_auth);
            } else {
                echo Paginator::pages((int)$_GET['pagenumber'], $how_total_pages, '/books');
            }
            ?>
        </div>
        <?php
    } else {
        ?>
        <div class="not_books">
            В данной библиотеке пока нет книг искомого автора.
        </div>

        <?php
    }
    ?>
</div>
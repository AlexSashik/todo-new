<div class="main">
    <h2>БИБЛИОТЕКА</h2>
    <div class="books_nav">
        <a href="/books">Все книги</a>
        <a href="#" class="book_nav_active">Все авторы</a>
    </div>
    <ol class="rounded-list">
        <?php
        while ($row = $res->fetch_assoc()) {
            echo '<li><a href="/books?id='.(int)$row['id'].'">'.htmlspecialchars($row['name']).'</a></li>';
        }
        ?>
    </ol>
</div>
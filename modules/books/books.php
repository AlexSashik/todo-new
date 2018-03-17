<?php
CORE::$META['title']  = 'Todo - books';
CORE::$END = '<link href="/skins/css/default/books1.00.css" rel="stylesheet" type="text/css">';

$_GET['pagenumber'] = $_GET['pagenumber'] ?? 1;

if (isset($_GET['id'])) {
    $author_res = q ("
        SELECT `authors`.`name`, `authors`.`img_name`, GROUP_CONCAT(`books2authors`.`book_id` SEPARATOR ', ') as `book_ids`
        FROM `authors`
        LEFT JOIN `books2authors` ON `books2authors`.`author_id` = `authors`.`id`
        WHERE `authors`.`id` =  ".(int)$_GET['id']."
        GROUP BY `authors`.`id`
    ");

    if (!$author_res->num_rows) {
        header ('Location: /books');
        exit;
    }

    $author_row = $author_res->fetch_assoc();

    if (empty($author_row['img_name'])) {
        $author_row['img_name'] = 'nophoto.png';
    }

    if (!is_null($author_row['book_ids'])) {
        list ($res, $how_total_pages) = Paginator::paginator_query(
            'books',
            "`books`.*, GROUP_CONCAT(`authors`.`name` SEPARATOR ', ') as `authors`",
            (int)$_GET['pagenumber'],
            "
                LEFT JOIN `books2authors` ON `books2authors`.`book_id` = `books`.`id`
                LEFT JOIN `authors` ON `books2authors`.`author_id` = `authors`.`id`
                WHERE `books`.`id` IN (".$author_row['book_ids'].")
                GROUP BY `books`.`id`
            "
        );
        $get_id_auth = '?id='.$_GET['id'];
    }
} else {
    list ($res, $how_total_pages) = Paginator::paginator_query(
        'books',
        '`books`.*, GROUP_CONCAT(`authors`.`name` SEPARATOR \', \') as `authors`',
        (int)$_GET['pagenumber'],
        "
            LEFT JOIN `books2authors` ON `books2authors`.`book_id` = `books`.`id`
            LEFT JOIN `authors` ON `books2authors`.`author_id` = `authors`.`id`
            GROUP BY `books`.`id`
        "
    );
}

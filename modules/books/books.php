<?php
CORE::$META['title']  = 'Todo - books';
CORE::$END = '<link href="/skins/css/default/books1.00.css" rel="stylesheet" type="text/css">';

if (isset($_GET['pagenumber'])) {
    $_GET['pagenumber'] = (int)$_GET['pagenumber'];
} else {
    $_GET['pagenumber'] = 1;
}

if (isset($_GET['id'])) {
    $author_res = q ("
        SELECT * FROM `authors`
        WHERE `id` = ".(int)$_GET['id']."
    ");
    if (!$author_res->num_rows) {
        header ('Location: /books');
        exit;
    }

    $author_search = $author_res->fetch_assoc();
    if (empty($author_search['img_name'])) {
        $author_search['img_name'] = 'nophoto.png';
    }

    $res_by_auth = q ("
		SELECT * FROM `books2authors`
		WHERE `author_id` = ".(int)$_GET['id']."
	");

    $ids_array = array();
    if ($res_by_auth->num_rows) {
        while ($row = $res_by_auth->fetch_assoc()) {
            $ids_array[] = $row['book_id'];
        }
        $ids = implode (', ', $ids_array);
        unset ($ids_array);
        list ($res, $how_total_pages) = Paginator::paginator_query('books', (int)$_GET['pagenumber'],"WHERE `id` IN (".$ids.")");
        $get_id_auth = '?id='.$_GET['id'];
    }
} else {
    list ($res, $how_total_pages) = Paginator::paginator_query('books', (int)$_GET['pagenumber']);
}

if (isset($res, $how_total_pages)) {

    // вывод авторов текущей выборки книг
    if ($res->num_rows) {
        $ids_array = array();
        while ($row = $res->fetch_assoc()) {
            if (empty($row['img_name'])) {
                $row['img_name'] = 'nophoto.png';
            }
            $data[$row['id']] = $row;
            $ids_array[] = $row['id'];
        }
        $ids = implode (', ', $ids_array);

        $ids_array = array();
        $relation_res = q("
            SELECT * FROM `books2authors`
            WHERE `book_id` IN (".$ids.")
        ");
        while ($row = $relation_res->fetch_assoc()) {
            $relation[$row['author_id']][] = $row['book_id'];
            $ids_array[] = $row['author_id'];
        }
        $ids = implode (', ', $ids_array);

        $author_res = q ("
            SELECT * FROM `authors`
            WHERE `id` IN (".$ids.")
        ");

        while ($row = $author_res->fetch_assoc()) {
            foreach ($relation[$row['id']] as $v) {
                if (!isset($data[$v]['auth'])) {
                    $data[$v]['auth'] = $row['name'];
                } else {
                    $data[$v]['auth'] =  $data[$v]['auth'].', '.$row['name'];
                }
            }
        }
    }

}


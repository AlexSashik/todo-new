<?php
class Paginator {
    static $howblocks   = 2;
    static $howpages    = 5;
    static $shownewt    = true;
    static $shownumbers = true;

    // функция, возвращающая "лимитированный" запрос от данного + количество фракций данной страницы
    static function paginator_query ($table, $page = 1, $additional = "") {
        if( $page < 1  ) {
            header ("Location: /".$_GET['module']);
            exit();
        }

        $first = self::$howblocks*($page-1);
        $res = q ("
            SELECT SQL_CALC_FOUND_ROWS * FROM `".es($table)."`
            ".$additional."
            LIMIT ".$first.", ".self::$howblocks."
        ");
        $res_count = q("SELECT FOUND_ROWS()");
        $row_count = $res_count->fetch_row();
        $how_total_pages = ceil($row_count[0] / self::$howblocks);

        if ($how_total_pages == 1) {
            self::$shownewt = false;
            self::$shownumbers = false;
        }

        if( $page > $how_total_pages ) {
            $res ->close();
            header ("Location: /".$_GET['module']);
            exit();
        }

        return array (
            $res,
            $how_total_pages
        );
    }

    // Функция, возвращающая интервал страниц в окрестности текущей страницы (нижнюю и верхнюю границы)
    static function activeInterval ($page, $how_total_pages) {
        $jump = (self::$howpages - 1)/2;
        $first_pag_number = 1;
        $last_pag_number = $how_total_pages;
        if ($page - $jump < 1) {
            $first_pag_number = 1;
            $last_pag_number  = 1 + 2*$jump;
        }
        if ($page + $jump > $how_total_pages) {
            $first_pag_number = $how_total_pages - 2*$jump;
            $last_pag_number  = $how_total_pages;
        }
        if ($page - $jump >= 1 && $page + $jump <= $how_total_pages) {
            $first_pag_number = ceil($page - $jump);
            $last_pag_number  = ceil($page + $jump);
        }
        if ($first_pag_number < 1) {
            $first_pag_number = 1;
        }
        if ($last_pag_number > $how_total_pages) {
            $last_pag_number = $how_total_pages;
        }
        return array(
            $first_pag_number,
            $last_pag_number
        );
    }

    // генерируем html-страницу, для вида(view)
    static function pages ($page, $how_total_pages, $link, $get_id_auth = "") {
        $res_string = '';
        $previous_page = ($page - 1 != 0) ? $page - 1 : false;
        $next_page = ($page + 1 <= $how_total_pages) ? $page + 1 : false;
        list($first_pag_number, $last_pag_number) = self::activeInterval($page, $how_total_pages);

        if (self::$shownewt && $previous_page !== false) {
            $res_string .= "<a href=\"".$link."/".$previous_page.$get_id_auth."\" class=\"next animate\"><i class=\"fa fa-arrow-left\" aria-hidden=\"true\"></i> Previous</a>";
        }
        if (Paginator::$shownumbers) {
            if ($first_pag_number != 1) {
                $res_string .= "<a href='".$link."/1".$get_id_auth."' class = 'numbers animate'>1</a>";
                if ($first_pag_number != 2) {
                    $res_string .= "<div class = 'ellipsis'>...</div>";
                }
            }

            for ($i = $first_pag_number; $i <= $last_pag_number; $i++) {
                if ($i != (int)$page) {
                    $res_string .= "<a href='".$link."/".$i.$get_id_auth."' class = 'numbers animate'>".$i."</a>";
                } else {
                    $res_string .= "<a href='".$link."/".$i.$get_id_auth."' class = 'numbers animate pag_active'>".$i."</a>";
                }
            }

            if ($last_pag_number != $how_total_pages) {
                if ($last_pag_number + 1 != $how_total_pages) {
                    $res_string .= "<div class = 'ellipsis'>...</div>";
                }
                $res_string .= "<a href='".$link."/".$how_total_pages.$get_id_auth."' class = 'numbers animate'>".$how_total_pages."</a>";
            }
        }
        if (self::$shownewt && $next_page !== false) {
            $res_string .= '<a href="'.$link.'/'.$next_page.$get_id_auth.'" class="next animate">Next <i class="fa fa-arrow-right" aria-hidden="true"></i></a>';
        }

        return $res_string;
    }
}
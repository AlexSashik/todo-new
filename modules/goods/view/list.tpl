<div class="main">
    <h2>СПИСОК ТОВАРОВ</h2>
    <div class="cat_nav">
        <a href="/goods" class="cat <?php if (isset($flag)) echo 'active';?>">Все</a>
        <?php
        foreach ($cat as $v) {
            if (isset($_GET['cat']) && $_GET['cat'] == $v) {
                echo '<a href="/goods/'.$v.'" class="cat active">'.htmlspecialchars($v).'</a>';
            } else {
                echo '<a href="/goods/'.$v.'" class="cat">'.htmlspecialchars($v).'</a>';
            }

        }
        ?>
    </div>

    <?php
    if (!$res->num_rows) {
        echo '<div class="search_not_found">Товаров в данной категории пока нет. Приносим наши извинения.</div>';
    } else {
        $i = 1;
        while($row = $res->fetch_assoc()) {
            if (($i-1) % 4 == 0) {
                echo '<div class="row_of_goods">';
            }
            ?>
            <div class="good">
                <?php
                echo '<a href="/goods/description/'.(int)$row['id'].'"><img src="/skins/img/default/goods/250x250/'.htmlspecialchars($row['main_img']).'" alt=""></a>';
                ?>
                <a href="/goods/description/<?php echo (int)$row['id']?>" class="name"><?php echo htmlspecialchars($row['name']);?></a>
                <div class="price <?php if ($row['is_in_sight'] == '0') echo 'out_of'; else echo 'in';?>_sight"> <?php echo htmlspecialchars($row['price']);?><span>грн</span> </div>
                <?php
                if ($row['is_in_sight'] != 0) {
                    echo '<a href="#" class="buy"><i class="fa fa-shopping-cart fa-2x" aria-hidden="true"></i></a>';
                } else {
                    echo '<div class="no_buy">Нет в наличии</div>';
                }
                ?>
            </div>
            <?php
            if ($i % 4 == 0) {
                echo '</div>';
            }
            $i++;
        }
        $res->close();
        if (($i-1) % 4 != 0 ) {
            echo '</div>';
        }
    }
    ?>
</div>
<div class="main">
    <h2><?php echo htmlspecialchars($row['name']);?></h2>
    <div class="desc_price_buy <?php if ($row['is_in_sight'] == '0') echo 'out_of_sight'; else echo 'in_sight';?>">
        <img class="desc_img" src="/skins/img/default/goods/250x250/<?php echo htmlspecialchars($row['main_img']);?>" alt="">
        <?php
        if ($row['is_in_sight'] == '0') {
            ?>
            <div class="desc_out_of_sight">Нет в наличии</div>
            <div class="desc_price"><?php echo htmlspecialchars($row['price']);?><span>грн</span></div>
            <?php
        } else {
            ?>
            <div class="desc_price"><?php echo htmlspecialchars($row['price']);?><span>грн</span></div>
            <a href="#" class="desc_buy"><i class="fa fa-shopping-cart fa-lg" aria-hidden="true"></i>Купить</a>
            <?php
        }
        ?>
    </div>
    <div class="desc_text">
        <div>
            <strong>Категория: </strong><?php echo htmlspecialchars($row['cat']);?><br><br>
            <strong>Описание: </strong><?php echo htmlspecialchars($row['text']);?><br><br>
            <?php if ($res_img->num_rows > 1) echo '<strong>Все фото товара: </strong>';?><br><br>
        </div>
        <?php
        if ($res_img->num_rows > 1) {
            $i = 1;
            while ($row_img = $res_img->fetch_assoc()) {
                echo '<img alt="" src="/skins/img/default/goods/100x100/'.htmlspecialchars($row_img['img_name']).'">';
                if ($i % 4 == 0) echo '<div class="clear"></div>';
                $i++;
            }
        }
        ?>
        <div class="clear"></div>
        <a href="/goods" class="desc_back"><i class="fa fa-arrow-left" aria-hidden="true"></i>К списку товаров</a>
        <div class="clear"></div>
    </div>
</div>
<div class="main container">
    <div class="container-fluid add_edit-header text-center">
        <span class="text-primary">
            Редактирование товара <?php if(isset($err)) echo '<span class="text-danger">(имеются ошибки!)</span>';?>
        </span>
    </div>

    <div class="container-fluid padding-top-bottom">
        <form method="post" enctype="multipart/form-data" class="form-horizontal">
            <div class="form-group  <?php if (isset($err['cat'])) echo 'has-error'; ?>">
                <label for="cat" class="control-label col-lg-3 col-md-3 col-sm-4">Выберите категорию товата:</label>
                <div class="col-lg-5 col-md-5 col-sm-5">
                    <select id="cat" class="form-control" name="cat[]">
                        <?php
                        $res_cat = q ("
                            SELECT * FROM `goods_cat`
                        ");
                        while ($row = $res_cat->fetch_assoc()) {
                            if ($row1['cat'] == $row['cat']) {
                                echo '<option selected value="'.htmlspecialchars($row['cat']).'">'.htmlspecialchars($row['cat']).'</option>';
                            } else {
                                echo '<option value="'.htmlspecialchars($row['cat']).'">'.htmlspecialchars($row['cat']).'</option>';
                            }
                        }
                        $res_cat->close();
                        ?>
                    </select>
                </div>
            </div>

            <div class="form-group <?php if (isset($err['name'])) echo 'has-error'; ?>">
                <label for="name" class="control-label col-lg-3 col-md-3 col-sm-4">Введите название товара:</label>
                <div class="col-lg-5 col-md-5 col-sm-5">
                    <input type="text" id="name" class="form-control" name="name" <?php echo 'value="'.htmlspecialchars(trim($row1['name'])).'"';?>>
                </div>
                <?php if (isset($err['name'])) echo '<div class="help-block col-lg-4 col-md-4 col-sm-3">'.$err['name'].'</div>'?>
            </div>

            <div class="form-group">
                <label class="control-label col-lg-3 col-md-3 col-sm-4">Активен ли товар:</label>
                <div class="col-lg-5 col-md-5 col-sm-5">
                    <div class="radio-inline">
                        <label>
                            <input id="yes" name="is_in_sight" type="radio" value="1"
                                <?php if ($row1['is_in_sight'] != '0') echo 'checked';?>
                            >
                            В продаже
                        </label>
                    </div>
                    <div class="radio-inline">
                        <label>
                            <input id="no" name="is_in_sight" type="radio" value="0"
                                <?php if ($row1['is_in_sight'] == '0') echo 'checked';?>
                            >
                            Отсутствует
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-group <?php if (isset($err['price'])) echo 'has-error'; ?>">
                <label for="price" class="control-label col-lg-3 col-md-3 col-sm-4">Цена(в грн):</label>
                <div class="col-lg-5 col-md-5 col-sm-5">
                    <input type="text" id="price" class="form-control" name="price" <?php echo 'value="'.htmlspecialchars($row1['price']).'"';?>>
                </div>
                <?php if (isset($err['price'])) echo '<div class="help-block col-lg-4 col-md-4 col-sm-3">'.$err['price'].'</div>'?>
            </div>

            <div class="form-group <?php if (isset($err['text'])) echo 'has-error'; ?>">
                <label for="text" class="control-label col-lg-3 col-md-3 col-sm-4">Описание товара:</label>
                <div class="col-lg-5 col-md-5 col-sm-5">
                    <textarea id="text" class="form-control textarea" name="text"><?php echo htmlspecialchars($row1['text']);?></textarea>
                </div>
                <?php if (isset($err['text'])) echo '<div class="help-block col-lg-4 col-md-4 col-sm-3">'.$err['text'].'</div>'?>
            </div>

            <?php
            $flag = true;
            while ($row2 = $res2->fetch_assoc()) {
                if($row2['img_name'] == 'no-photo.jpg') $flag = false;
                ?>
                <hr>
                <div class="container-fluid text-right">
                    <?php if (!$row2['is_main']) echo '<a class="text-danger" href="/admin/goods/edit/'.(int)$_GET['id'].'/'.$row2['id'].'" onclick="return del()">Удалить фото</a>' ?>
                </div>
                <div class="form-group <?php if (isset($err[$row2['id']])) echo 'has-error'; ?>">
                    <label class="control-label col-lg-3 col-md-3 col-sm-4">Изменить <?php if ($row2['is_main']) echo 'главное';?> фото товара:</label>
                    <div class="control-label col-lg-2 col-md-2 col-sm-2">
                        <img class="img-thumbnail" src="/skins/img/default/goods/100x100/<?php echo htmlspecialchars($row2['img_name'])?>" alt="">
                    </div>
                    <div class="file_div col-lg-3 col-md-3 col-sm-3">
                        <input class = "photo_label" type="file" name="<?php echo htmlspecialchars($row2['id'])?>">
                    </div>
                    <?php if (isset($err[$row2['id']])) echo '<div class="help-block col-lg-3 col-md-3 col-sm-4">'.$err[$row2['id']].'</div>';?>
                </div>

                <?php
            }
            if ($flag) {
                ?>
                <hr>
                <div class="form-group <?php if (isset($err['new_photo'])) echo 'has-error'; ?>">
                    <label for="picture" class="control-label col-lg-3 col-md-3 col-sm-4">Добавить новое фото товара:</label>
                    <div class="file_div col-lg-5 col-md-5 col-sm-5">
                        <label for="picture" class="file_label text-center">
                            <i class="glyphicon glyphicon-open"></i>
                            <span id="file_label_span">Выберите фото</span>
                        </label>
                        <input type="file" id="picture" class="form-control" name="new_photo">
                    </div>
                    <?php if (isset($err['new_photo'])) echo '<div class="help-block col-lg-4 col-md-4 col-sm-4">'.$err['new_photo'].'</div>';?>
                </div>
                <?php
            }
            ?>

            <div class="text-right col-lg-9 col-md-9 col-sm-9">
                <a href="/admin/goods" class="btn btn-danger btn-adapt"><i class="glyphicon glyphicon-ban-circle"></i> Отмена</a>
                <button type="submit" class="btn btn-success btn-adapt"><i class="glyphicon glyphicon-plus-sign"></i> Редактировать</button>
            </div>
        </form>
    </div>

</div>

<?php if (isset($info_name)) { ?>
    <div id="modal" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-<?php if($info_type == 'success') echo 'success'; else echo 'warning';?> panel-heading">
                    <button class="close" data-dismiss="modal">x</button>
                    <h4 class="modal-title text-center"><?php echo $info_name;?></h4>
                </div>
                <div class="modal-body"><?php echo $info_text;?></div>
                <div class="modal-footer">
                    <button class="btn btn-<?php if($info_type == 'success') echo 'success'; else echo 'warning';?>" data-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('#modal').modal();
    </script>
    <?php
}
?>
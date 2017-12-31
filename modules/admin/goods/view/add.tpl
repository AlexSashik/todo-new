<div class="main container">
    <div class="container-fluid add_edit-header text-center">
        <span class="text-primary">Добавление нового товара</span>
    </div>
    <div class="container-fluid padding-top-bottom">
        <form method="post" enctype="multipart/form-data" class="form-horizontal">
            <div class="form-group  <?php if (isset($err['cat'])) echo 'has-error'; ?>">
                <label for="cat" class="control-label col-lg-3 col-md-3 col-sm-4">Выберите категорию товата:</label>
                <div class="col-lg-5 col-md-5 col-sm-5">
                    <select id="cat" class="form-control" name="cat[]">
                        <option>&nbsp;</option>
                        <?php
                        $res_cat = q ("
                            SELECT * FROM `goods_cat`
                        ");
                        while ($row = $res_cat->fetch_assoc()) {
                            if (isset($_POST['cat'][0]) && $_POST['cat'][0] == $row['cat']) {
                                echo '<option selected value="'.htmlspecialchars($row['cat']).'">'.htmlspecialchars($row['cat']).'</option>';
                            } else {
                                echo '<option value="'.htmlspecialchars($row['cat']).'">'.htmlspecialchars($row['cat']).'</option>';
                            }
                        }
                        $res_cat->close();
                        ?>
                    </select>
                </div>
                <?php if (isset($err['cat'])) echo '<div class="help-block col-lg-4 col-md-4 col-sm-3">'.$err['cat'].'</div>'?>
            </div>

            <div class="form-group <?php if (isset($err['name'])) echo 'has-error'; ?>">
                <label for="name" class="control-label col-lg-3 col-md-3 col-sm-4">Введите название товара:</label>
                <div class="col-lg-5 col-md-5 col-sm-5">
                    <input type="text" id="name" class="form-control" name="name" <?php if(isset($_POST['name'])) echo 'value="'.htmlspecialchars(trim($_POST['name'])).'"';?>>
                </div>
                <?php if (isset($err['name'])) echo '<div class="help-block col-lg-4 col-md-4 col-sm-3">'.$err['name'].'</div>'?>
            </div>

            <div class="form-group">
                <label class="control-label col-lg-3 col-md-3 col-sm-4">Активен ли товар:</label>
                <div class="col-lg-5 col-md-5 col-sm-5">
                    <div class="radio-inline">
                        <label>
                            <input id="yes" name="is_in_sight" type="radio" value="1"
                                <?php
                                if (!isset($_POST['is_in_sight']) || (isset($_POST['is_in_sight']) && $_POST['is_in_sight'] == '1'))
                                    echo 'checked';
                                ?>
                            >
                            В продаже
                        </label>
                    </div>
                    <div class="radio-inline">
                        <label>
                            <input id="no" name="is_in_sight" type="radio" value="0"
                                <?php if (isset($_POST['is_in_sight']) && $_POST['is_in_sight'] == '0')
                                    echo 'checked';
                                ?>
                            >
                            Отсутствует
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-group <?php if (isset($err['price'])) echo 'has-error'; ?>">
                <label for="price" class="control-label col-lg-3 col-md-3 col-sm-4">Цена(в грн):</label>
                <div class="col-lg-5 col-md-5 col-sm-5">
                    <input type="text" id="price" class="form-control" name="price" <?php if(isset($_POST['price'])) echo 'value="'.htmlspecialchars(trim($_POST['price'])).'"';?>>
                </div>
                <?php if (isset($err['price'])) echo '<div class="help-block col-lg-4 col-md-4 col-sm-3">'.$err['price'].'</div>'?>
            </div>

            <div class="form-group <?php if (isset($err['text'])) echo 'has-error'; ?>">
                <label for="text" class="control-label col-lg-3 col-md-3 col-sm-4">Описание товара:</label>
                <div class="col-lg-5 col-md-5 col-sm-5">
                    <textarea id="text" class="form-control textarea" name="text"><?php if(isset($_POST['text'])) echo htmlspecialchars(trim($_POST['text']));?></textarea>
                </div>
                <?php if (isset($err['text'])) echo '<div class="help-block col-lg-4 col-md-4 col-sm-3">'.$err['text'].'</div>'?>
            </div>

            <div class="form-group <?php if (isset($err['picture'])) echo 'has-error'; ?>">
                <label for="picture" class="control-label col-lg-3 col-md-3 col-sm-4">Прикрепите фото товара:</label>
                <div class="file_div col-lg-5 col-md-5 col-sm-5">
                    <label for="picture" class="file_label text-center">
                        <i class="glyphicon glyphicon-open"></i>
                        <span id="file_label_span">Выберите фото</span>
                    </label>
                    <input type="file" id="picture" class="form-control" name="picture[]" multiple>
                </div>
                <?php if (isset($err['picture'])) echo '<div class="help-block col-lg-4 col-md-4 col-sm-4">'.$err['picture'].'</div>';?>
            </div>
            <div class="text-right col-lg-9 col-md-9 col-sm-9">
                <a href="/admin/goods" class="btn btn-danger btn-adapt"><i class="glyphicon glyphicon-ban-circle"></i> Отмена</a>
                <button type="submit" class="btn btn-success btn-adapt"><i class="glyphicon glyphicon-plus-sign"></i> Добавить</button>
            </div>
        </form>
    </div>
</div>
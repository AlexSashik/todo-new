<div class="main container">
    <div class="container-fluid add_edit-header text-center">
        <span class="text-primary">
           Редактирование автора
        </span>
    </div>

    <div class="container-fluid padding-top-bottom">
        <form method="post" enctype="multipart/form-data" class="form-horizontal">
            <div class="form-group <?php if (isset($err['name'])) echo 'has-error'; ?>">
                <label for="name" class="control-label col-lg-3 col-md-3 col-sm-4">Имя и фамилия автора (полностью, через пробел)</label>
                <div class="col-lg-5 col-md-5 col-sm-5">
                    <input type="text" id="name" class="form-control" name="name" value="<?php if (isset($row['name'])) echo htmlspecialchars($row['name']);?>">
                </div>
                <?php if (isset($err['name'])) echo '<div class="help-block col-lg-4 col-md-4 col-sm-3">'.$err['name'].'</div>'?>
            </div>

            <div class="form-group <?php if (isset($err['yob'])) echo 'has-error'; ?>">
                <label for="yob" class="control-label col-lg-3 col-md-3 col-sm-4">Год рождения:</label>
                <div class="col-lg-5 col-md-5 col-sm-5">
                    <input type="text" id="yob" class="form-control" name="yob" value="<?php if (isset($row['yob'])) echo htmlspecialchars($row['yob']);?>">
                </div>
                <?php if (isset($err['yob'])) echo '<div class="help-block col-lg-4 col-md-4 col-sm-3">'.$err['yob'].'</div>'?>
            </div>

            <div class="form-group <?php if (isset($err['yod'])) echo 'has-error'; ?>">
                <label for="yod" class="control-label col-lg-3 col-md-3 col-sm-4">Год смерти:</label>
                <div class="col-lg-5 col-md-5 col-sm-5">
                    <input type="text" id="yod" class="form-control" name="yod" value="<?php if (isset($row['yod'])) echo htmlspecialchars($row['yod']);?>">
                </div>
                <?php if (isset($err['yod'])) echo '<div class="help-block col-lg-4 col-md-4 col-sm-3">'.$err['yod'].'</div>'?>
            </div>

            <div class="form-group">
                <label class="control-label col-lg-3 col-md-3 col-sm-4">Текущее фото автора:</label>
                <div class="col-lg-2 col-md-2 col-sm-2 text-left">
                    <img class="img-thumbnail" src="/skins/img/default/authors/100x100/<?php echo htmlspecialchars($row['img_name']);?>" alt="">
                </div>
            </div>

            <div class="form-group <?php if (isset($err['picture'])) echo 'has-error'; ?>">
                <label for="picture" class="control-label col-lg-3 col-md-3 col-sm-4">Изменить фото автора:</label>
                <div class="file_div col-lg-5 col-md-5 col-sm-5">
                    <label for="picture" class="file_label text-center">
                        <i class="glyphicon glyphicon-open"></i>
                        <span id="file_label_span">Выберите фото</span>
                    </label>
                    <input type="file" id="picture" class="form-control" name="picture">
                </div>
                <?php if (isset($err['picture'])) echo '<div class="help-block col-lg-4 col-md-4 col-sm-4">'.$err['picture'].'</div>';?>
            </div>

            <div class="text-right col-lg-9 col-md-9 col-sm-9">
                <a href="/admin/authors" class="btn btn-danger btn-adapt"><i class="glyphicon glyphicon-ban-circle"></i> Отмена</a>
                <button type="submit" class="btn btn-success btn-adapt"><i class="glyphicon glyphicon-plus-sign"></i> Редактировать</button>
            </div>
        </form>
    </div>
</div>
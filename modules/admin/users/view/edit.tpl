<div class="main container">
    <div class="container-fluid add_edit-header text-center">
        <span class="text-primary">
           Редактирование пользователя
        </span>
    </div>

    <div class="container-fluid padding-top-bottom">
        <form method="post" enctype="multipart/form-data" class="form-horizontal">
            <div class="form-group <?php if (isset($err['login'])) echo 'has-error'; ?>">
                <label for="login" class="control-label col-lg-3 col-md-3 col-sm-4">Логин:</label>
                <div class="col-lg-5 col-md-5 col-sm-5">
                    <input type="text" id="login" class="form-control" name="login" <?php echo 'value="'.htmlspecialchars($row['login']).'"';?>>
                </div>
                <?php if (isset($err['login'])) echo '<div class="help-block col-lg-4 col-md-4 col-sm-3">'.$err['login'].'</div>'?>
            </div>

            <div class="form-group <?php if (isset($err['email'])) echo 'has-error'; ?>">
                <label for="email" class="control-label col-lg-3 col-md-3 col-sm-4">Email:</label>
                <div class="col-lg-5 col-md-5 col-sm-5">
                    <input type="email" id="email" class="form-control" name="email" <?php echo 'value="'.htmlspecialchars($row['email']).'"';?>>
                </div>
                <?php if (isset($err['email'])) echo '<div class="help-block col-lg-4 col-md-4 col-sm-3">'.$err['email'].'</div>'?>
            </div>

            <div class="form-group <?php if (isset($err['pass'])) echo 'has-error'; ?>">
                <label for="pass" class="control-label col-lg-3 col-md-3 col-sm-4">Изменить пароль:</label>
                <div class="col-lg-5 col-md-5 col-sm-5">
                    <input type="text" id="pass" class="form-control" name="pass" <?php echo 'value="'.htmlspecialchars($row['pass']).'"';?>>
                </div>
                <?php if (isset($err['pass'])) echo '<div class="help-block col-lg-4 col-md-4 col-sm-3">'.$err['pass'].'</div>'?>
            </div>

            <div class="form-group <?php if (isset($err['age'])) echo 'has-error'; ?>">
                <label for="age" class="control-label col-lg-3 col-md-3 col-sm-4">Возраст (число):</label>
                <div class="col-lg-5 col-md-5 col-sm-5">
                    <input type="text" id="age" class="form-control" name="age" <?php echo 'value="'.htmlspecialchars($row['age']).'"';?>>
                </div>
                <?php if (isset($err['age'])) echo '<div class="help-block col-lg-4 col-md-4 col-sm-3">'.$err['age'].'</div>'?>
            </div>

            <div class="form-group  <?php if (isset($err['cat'])) echo 'has-error'; ?>">
                <label for="role" class="control-label col-lg-3 col-md-3 col-sm-4">Cтатус:</label>
                <div class="col-lg-5 col-md-5 col-sm-5">
                    <select id="role" class="form-control" name="role[]">
                        <option <?php if ($row['role'] == 0) echo 'selected';?> value="0">Не активен</option>
                        <option <?php if ($row['role'] == 1) echo 'selected';?> value="1">Активен</option>
                        <option <?php if ($row['role'] == 2) echo 'selected';?> value="2">Забанен</option>
                        <option <?php if ($row['role'] == 3) echo 'selected';?> value="3">Админ</option>
                    </select>
                </div>
            </div>

            <div class="form-group <?php if (isset($err['reg_date'])) echo 'has-error'; ?>">
                <label for="reg_date" class="control-label col-lg-3 col-md-3 col-sm-4">Дата регистрации:</label>
                <div class="col-lg-5 col-md-5 col-sm-5">
                    <input placeholder="<?php echo date('Y-m-d');?>" type="text" id="reg_date" class="form-control" name="reg_date" <?php echo 'value="'.htmlspecialchars($row['date']).'"';?>>
                </div>
                <?php if (isset($err['reg_date'])) echo '<div class="help-block col-lg-4 col-md-4 col-sm-3">'.$err['reg_date'].'</div>'?>
            </div>

            <div class="form-group <?php if (isset($err['last_active_date'])) echo 'has-error'; ?>">
                <label for="last_active_date" class="control-label col-lg-3 col-md-3 col-sm-4">Последняя активность:</label>
                <div class="col-lg-5 col-md-5 col-sm-5">
                    <input placeholder="<?php echo date('Y-m-d');?>" type="text" id="last_active_date" class="form-control" name="last_active_date" <?php echo 'value="'.htmlspecialchars($row['lastactive']).'"';?>>
                </div>
                <?php if (isset($err['last_active_date'])) echo '<div class="help-block col-lg-4 col-md-4 col-sm-3">'.$err['last_active_date'].'</div>'?>
            </div>

            <div class="form-group">
                <label class="control-label col-lg-3 col-md-3 col-sm-4">Текущий аватар:</label>
                <div class="col-lg-2 col-md-2 col-sm-2 text-left">
                    <img class="img-thumbnail" src="/skins/img/default/users/100x100/<?php echo htmlspecialchars($row['avatar']);?>" alt="">
                </div>
            </div>

            <div class="form-group <?php if (isset($err['picture'])) echo 'has-error'; ?>">
                <label for="picture" class="control-label col-lg-3 col-md-3 col-sm-4">Изменить аватар:</label>
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
                <a href="/admin/users" class="btn btn-danger btn-adapt"><i class="glyphicon glyphicon-ban-circle"></i> Отмена</a>
                <button type="submit" class="btn btn-success btn-adapt"><i class="glyphicon glyphicon-plus-sign"></i> Редактировать</button>
            </div>
        </form>
    </div>
</div>
<div class="main container">
    <div class="container-fluid add_edit-header text-center">
        <span class="text-primary">Добавление новой книги</span>
    </div>

    <div class="container-fluid padding-top-bottom">

        <form method="post" enctype="multipart/form-data" class="form-horizontal">
            <div class="form-group  <?php if (isset($err['auth'])) echo 'has-error'; ?>">
                <label for="auth2" class="control-label col-lg-3 col-md-3 col-sm-4">
                    <span id="show_help">Авторы</span> (для выбора нескольких удерживайте кнопку Ctrl):
                </label>
                <div class="col-lg-5 col-md-5 col-sm-5">
                    <select id="auth2" class="form-control" name="auth[]" multiple>
                        <?php
                        while($row = $res->fetch_assoc()) {
                            if (isset($_POST['auth']) && in_array($row['id'], $_POST['auth']) ) {
                                echo '<option selected value="'.(int)$row['id'].'">'.htmlspecialchars($row['name']).'</option>';
                            } else {
                                echo '<option value="'.(int)$row['id'].'">'.htmlspecialchars($row['name']).'</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
                <?php if (isset($err['auth'])) echo '<div class="help-block col-lg-4 col-md-4 col-sm-3">'.$err['auth'].'</div>'?>

                <div id="help">
                    <img alt="" src="/skins/img/admin/info.png">
                    <div class="info_text">
                        <p>Добавление новых авторов</p>
                        Для добавления новых авторов перейдите на вкладку "авторы" в меню навигации страницы, либо щелкните
                        <a href="/admin/authors/add">здесь.</a>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>

            <div class="form-group <?php if (isset($err['name'])) echo 'has-error'; ?>">
                <label for="name" class="control-label col-lg-3 col-md-3 col-sm-4">Название книги:</label>
                <div class="col-lg-5 col-md-5 col-sm-5">
                    <input type="text" id="name" class="form-control" name="name"
                        <?php if (isset($_POST['name'])) echo 'value="'.htmlspecialchars($_POST['name']).'"';?>
                    >
                </div>
                <?php if (isset($err['name'])) echo '<div class="help-block col-lg-4 col-md-4 col-sm-3">'.$err['name'].'</div>'?>
            </div>

            <div class="form-group  <?php if (isset($err['year'])) echo 'has-error'; ?>">
                <label for="year" class="control-label col-lg-3 col-md-3 col-sm-4">Год издания:</label>
                <div class="col-lg-5 col-md-5 col-sm-5">
                    <select id="year" class="form-control" name="year[]">
                        <option value="empty"></option>
                        <?php
                        if (isset($_POST['year'][0])) {
                            for ($i = 1900; $i <= (int)date("Y"); $i++) {
                                if ($_POST['year'][0] == $i) {
                                    echo '<option selected value="'.$i.'">'.$i.'</option>';
                                } else {
                                    echo '<option value="'.$i.'">'.$i.'</option>';
                                }
                            }
                        } else {
                            for ($i = 1900; $i <= (int)date("Y"); $i++) {
                                echo '<option value="' . $i . '">' . $i . '</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="form-group <?php if (isset($err['text'])) echo 'has-error'; ?>">
                <label for="text" class="control-label col-lg-3 col-md-3 col-sm-4">Описание книги:</label>
                <div class="col-lg-5 col-md-5 col-sm-5">
                    <textarea id="text" class="form-control textarea" name="text"><?php if(isset($_POST['text'])) echo htmlspecialchars(trim($_POST['text']));?></textarea>
                </div>
                <?php if (isset($err['text'])) echo '<div class="help-block col-lg-4 col-md-4 col-sm-3">'.$err['text'].'</div>'?>
            </div>

            <div class="form-group <?php if (isset($err['picture'])) echo 'has-error'; ?>">
                <label for="picture" class="control-label col-lg-3 col-md-3 col-sm-4">Прикрепите фото книги:</label>
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
                <a href="/admin/books" class="btn btn-danger btn-adapt"><i class="glyphicon glyphicon-ban-circle"></i> Отмена</a>
                <button type="submit" class="btn btn-success btn-adapt"><i class="glyphicon glyphicon-plus-sign"></i> Добавить</button>
            </div>
        </form>
    </div>
</div>
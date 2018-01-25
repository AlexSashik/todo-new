<div class="main">
    <?php
    if (!isset($_SESSION['user'])) {
        ?>
        <h2>ВХОД</h2>
        <form method="post" class="form_enter">
            <div class="reg_header">
                <label for="login">Логин</label>
                <input id="login" class="lep" type="text" name="login" <?php if (isset($_POST['login'])) echo 'value="'.htmlspecialchars($_POST['login']).'"';?> required>
                <?php
                if (isset($errors['login_err'])) {
                    if ($errors['login_err'] === false) {
                        echo '<div class="info_success"><i class="fa fa-check" aria-hidden="true"></i>Логин принят</div>';
                    } else {
                        echo '<div class="info_err"><i class="fa fa-times" aria-hidden="true"></i>'.$errors['login_err'].'</div>';
                    }
                }
                ?>
                <div class="clear"></div>

                <label for="pass">Пароль</label>
                <input id="pass" class="lep" type="password" name="pass" <?php if (isset($_POST['pass'])) echo 'value="'.htmlspecialchars($_POST['pass']).'"';?> required>
                <?php
                if (isset($errors['pass_err'])) {
                    echo '<div class="info_err"><i class="fa fa-times" aria-hidden="true"></i>'.$errors['pass_err'].'</div>';
                }
                ?>
                <div class="clear"></div>

                <label for="remember">Запомнить меня?</label>
                <input id="remember" class="remember" type="checkbox" name="remember[]">
                <div class="clear"></div>
            </div>
            <div class="reg_footer">
                <input class="submit" type="submit" name="reg" value="Авторизоваться">
                <p>или войти через</p>
                <a title="Войти через facebook" href="https://www.facebook.com/v2.11/dialog/oauth?client_id=<?php echo Core::$ID?>&redirect_uri=<?php echo Core::$URL?>&response_type=code&scope=public_profile,email">
                    <i class="fa fa-facebook" aria-hidden="true"></i> Facebook
                </a>
            </div>
        </form>

        <?php if (isset($activation)) { ?>
            <div id="info_back"></div>
            <div id="info_text">
                <div class="info_header">Завершение регистрации</div>
                <div class="info_main">
                    <img src="/skins/img/default/<?php if($activation) echo 'check-icon.png'; else echo 'attantion.png';?>" alt="">
                    <?php
                    if($activation) {
                        echo '<p>Активация Вашего аккаунта прошла успешно. Для входа на сайт заполните поля авторизации.</p>';
                    } else {
                        echo '<p>Вы перешли по несуществующей ссылке.</p>';
                    }
                    ?>
                    <div id="info_close">OK</div>
                </div>
            </div>
            <?php
        }
        if (isset($errors['active_err'])) { ?>
            <div id="info_back"></div>
            <div id="info_text">
                <div class="info_header"><?php echo $errors['active_err']['header'];?></div>
                <div class="info_main">
                    <img src="<?php echo $errors['active_err']['img'];?>" alt="">
                    <p><?php echo $errors['active_err']['text'];?></p>
                    <div id="info_close">OK</div>
                </div>
            </div>
            <?php
        }
    } else {
        ?>

        <div class="profile">
            <h2>МОЙ ПРОФИЛЬ</h2>
            <form method="post" class="form_profile" enctype="multipart/form-data">
                <div class="left_side">
                    <img src="/skins/img/default/users/100x100/<?php
                    if (empty(User::$avatar)) echo 'noavatar.png';
                    else echo htmlspecialchars(User::$avatar);?>" alt ="">
                    <div class="file_div">
                        <label for="file" class="file_label">
                            <i class="fa fa-upload" aria-hidden="true"></i>
                            <span id="file_label_span">Новый аватар</span>
                        </label>
                        <input id="file" type="file" name="file">
                        <?php if (isset($err['file'])) echo '<span class="err_color">'.$err['file'].'</span>';?>
                    </div>
                </div>
                <div class="center_side">
                    <p>Общая информация</p>
                    <?php
                    if (isset($err['gen_info'])) {
                        echo '<div class="info_err"><i class="fa fa-times" aria-hidden="true"></i>'.$err['gen_info'].'</div>';
                    }
                    ?>
                    <div class="clear"></div>
                    <label class="label_for_info" for="login">Логин:</label>
                    <input id="login" class="lep <?php if (isset($err['login'])) echo 'bg_err';?>" type="text" name="login" value="<?php
                    if (isset($_POST['login'])) echo hc($_POST['login']);
                    else echo hc(User::$login);
                    ?>">
                    <div class="clear"></div>
                    <label class="label_for_info" for="email">Email:</label>
                    <input id="email" class="lep <?php if (isset($err['email'])) echo 'bg_err';?>" type="email" name="email" value="<?php
                    if (isset($_POST['email'])) echo hc($_POST['email']);
                    else echo hc(User::$email);
                    ?>">
                    <div class="clear"></div>
                    <label class="label_for_info" for="age">Возраст:</label>
                    <input id="age" <?php if (isset($err['age'])) echo 'class="bg_err"';?> type="text" name="age" value="<?php
                    if (isset($_POST['age'])) echo hc($_POST['age']);
                    else if (User::$age) echo hc(User::$age);?>">
                    <div class="clear"></div>
                    <p>Изменить пароль</p>
                    <?php
                    if (isset($err['pass'])) {
                        echo '<div class="info_err"><i class="fa fa-times" aria-hidden="true"></i>'.$err['pass'].'</div>';
                    }
                    ?>
                    <div class="clear"></div>
                    <label for="pass" class="label_for_pass">Новый пароль:</label>
                    <input id="pass" class="pass" type="password" name="pass">
                    <div class="clear"></div>
                    <label for="pass_repeat" class="label_for_pass">Повторите пароль:</label>
                    <input id="pass_repeat" class="pass" type="password" name="pass_repeat">
                    <div class="clear"></div>
                </div>
                <div class="right_side">
                    <p>Статус на сайте</p>
                    <img src="/skins/img/default/users/<?php if (User::$role == 'admin') echo 'admin'; else echo 'user';?>_role.jpg" alt="">
                </div>
                <div class="clear"></div>
                <div class="reg_footer">
                    <input class="submit" type="submit" name="reg" value="Редактировать измененные данные">
                </div>
            </form>
        </div>
        <?php if (isset($info_name)) { ?>
            <div id="info_back"></div>
            <div id="info_text">
                <div class="info_header"> <?php echo $info_name;?></div>
                <div class="info_main">
                    <img src="/skins/img/default/<?php if($info_type == 'success') echo 'check-icon.png'; else echo 'attantion.png';?>" alt="">
                    <p><?php echo $info_text;?></p>
                    <div id="info_close">OK</div>
                </div>
            </div>
            <?php
        }
    }
    ?>
</div>
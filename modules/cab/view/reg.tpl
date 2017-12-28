<div class="main">
    <h2>РЕГИСТРАЦИЯ</h2>
    <form method="post" class="form_enter" onsubmit="return checkRegForm();">
        <div class="reg_header">
            <label for="login">Логин*</label>
            <input required id="login" class="lep" type="text" name="login" <?php if (isset($_POST['login'])) echo 'value="'.htmlspecialchars($_POST['login']).'"';?>>
            <?php
            if (isset($errors['login_err'])) {
                echo '<div id="log_err" class="info_err"><i class="fa fa-times" aria-hidden="true"></i>'.$errors['login_err'].'</div>';
            }
            ?>
            <div class="clear"></div>

            <label for="email">Email*</label>
            <input required id="email" class="lep" type="email" name="email" <?php if (isset($_POST['email'])) echo 'value="'.htmlspecialchars($_POST['email']).'"';?>>
            <?php
            if (isset($errors['email_err'])) {
                echo '<div id="email_err" class="info_err"><i class="fa fa-times" aria-hidden="true"></i>'.$errors['email_err'].'</div>';
            }
            ?>
            <div class="clear"></div>

            <label for="pass">Пароль*</label>
            <input required id="pass" class="lep" type="password" name="pass" <?php if (isset($_POST['pass'])) echo 'value="'.htmlspecialchars($_POST['pass']).'"';?> >
            <?php
            if (isset($errors['pass_err'])) {
                echo '<div id="pass_err" class="info_err"><i class="fa fa-times" aria-hidden="true"></i>'.$errors['pass_err'].'</div>';
            }
            ?>
            <div class="clear"></div>

        </div>
        <div class="reg_footer">
            <input class="submit" type="submit" name="reg" value="Зарегистрироваться">
        </div>
    </form>

    <?php if (isset($info)) { ?>
        <div id="info_back"></div>
        <div id="info_text">
            <div class="info_header">Завершение регистрации</div>
            <div class="info_main">
                <img src="/skins/img/default/check-icon.png" alt="">
                <p><?php echo $info;?></p>
                <div id="info_close">OK</div>
            </div>
        </div>
        <?php
    }
    ?>
</div>
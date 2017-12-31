<?php if(!isAdmin()) { /* Login */ ?>
    <div class="main_cab">
        <h2>ВХОД В АДМИНКУ</h2>
        <form action="/<?=$_GET['route']; ?>" method="post" class="form_enter">
            <div class="reg_header">
                <input placeholder="Логин" class="logpas" type="text" name="login" <?php if (isset($_POST['login'])) echo 'value="'.htmlspecialchars($_POST['login']).'"';?> required>
                <div class="clear"></div>

                <input placeholder="Пароль" class="logpas" type="password" name="pass" <?php if (isset($_POST['pass'])) echo 'value="'.htmlspecialchars($_POST['pass']).'"';?> required>
                <div class="clear"></div>

            </div>
            <div class="reg_footer">
                <input class="submit" type="submit" value="ВОЙТИ">
            </div>
        </form>
        <div class="footer">
            <div>НА ГЛАВНУЮ:</div>
            <a href="/" class="img"></a>
        </div>
    </div>
    <?php
    if (!empty(User::$data['role'])) { ?>
        <div id="info_back"></div>
        <div id="info_text">
            <div class="info_header">Вход не выполнен</div>
            <div class="info_main">
                <img src="/skins/img/admin/goods/error.png" alt="">
                <div>Вы не администратор. Доступ к ресурсу предоставляется только администраторам сайта.</div>
            </div>
            <div id="info_close">OK</div>
        </div>
        <?php
    }

    if (isset($error)) {
        ?>
        <div id="info_err">
            <div id="info_err_close"> </div>
            <div class="clear"></div>
            <div class="content">
                <div class="img"> </div>
                <div class="text"><?php
                    if (!empty($error['login'])) echo $error['login'];
                    else echo $error['password'];?></div>
            </div>
        </div>
        <?php
    }
} else { ?>
    <main class="main container">
        <h2>АДМИНКА</h2>
        <p>
            Добро пожаловать в админку сайта <a href="/" target="_blank">http://todo.kh.ua</a>! В данном разделе Вы можете управлять контентом сайта.
            Среди прочего, имеется возможность добавления, редактирования и удаления соответствующих элементов таких
            разделов, как:
        </p>
        <ul>
            <li>товары;</li>
            <li>пользователи;</li>
            <li>книги;</li>
            <li>авторы книг.</li>
        </ul>
        <p>
            Раздел "Комментарии" и "Чат" доступен для редактирования из основной части сайта и поэтому не был вынесен отдельно в
            панель администратора.
        </p>
        <p>
            Перейти на указанные страницы можно воспользовавшись навигационной панелью в верхней части админки. Результат
            внесенных изменений всегда можно посмотреть на сайте, щелкнув на кнопку "Просмотр сайта" в правом верхнем углу
            навигационной панели.
        </p>
        <p>
            Покинуть админ панель можно нажав на кнопку "Exit" в правом верхнем углу станицы. При этом Вы останетесь
            авторизованным пользователем.
        </p>
    </main>

    <?php if (isset($info['success_autoriz'])) { ?>
        <div id="modal" class="modal fade" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-success panel-heading">
                        <button class="close" data-dismiss="modal">x</button>
                        <h4 class="modal-title text-center">Вход в админ панель выполнен</h4>
                    </div>
                    <div class="modal-body">
                        <p>
                            Добро пожаловать, <?php echo htmlspecialchars($_SESSION['user']['login']);?>! Вам предоставлен доступ
                            к панели администратора сайта <a class="animate" href="/" target="_blank">todo.kh.ua</a>
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-success" data-dismiss="modal">Закрыть</button>
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
<?php } ?>
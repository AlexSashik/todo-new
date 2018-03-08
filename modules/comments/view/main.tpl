<script>
    var status = <?php
        if (!is_null(User::$data)) {
            if (User::$role == 'admin') {
                echo 5;
            } else {
                echo 1;
            }
        } else {
            echo 0;
        }
        ?>;
</script>

<div id="info_back"></div>
<div id="fixed_back">
    <div class="success-comment">
        <div class="success-comment-header">
            <div id="success_comment_title"></div>
            <i id="close_times" class="fa fa-times fa-lg" aria-hidden="true" title="Закрыть"></i>
        </div>
        <div id="success_comment_body">
            <!-- Текст добавится средствами js после успешного добавления комментария -->
        </div>
        <div class="success-comment-footer">
            <input id="close_button" type="button" value="Закрыть">
        </div>
    </div>
</div>


<div class="main" id="main">
    <h2>ДОБАВЬ СВОЙ ОТЗЫВ</h2>
    <form method="post">
        <div id="comm_count" data-count="<?php echo $comments_count;?>"><?php echo $comments_count;?> комментари<?php echo $ending;?></div>

        <div class="photo">
            <?php
            if (isset(User::$data)) {
                if (!empty(User::$avatar)) {
                    echo '<img alt="" src="/skins/img/default/users/100x100/'.htmlspecialchars(User::$data['avatar']).'">';
                } else {
                    echo '<img alt="" src="/skins/img/default/users/100x100/noavatar.png">';
                }
                echo '<br>'.htmlspecialchars(User::$login);
            } else {
                echo '<img alt="" src="/skins/img/default/users/100x100/noavatar.png">';
            }
            ?>
        </div>
        <div class="field">
            <?php
            if (!isset(User::$data)) {
                ?>
                <input id="login" type="text" name="login" placeholder="Ваше имя..." class="login_email">
                <div id="login_err" class="err_div"></div>

                <input id="email" type="email" name="email" placeholder="Ваш e-mail..." class="login_email">
                <div id="email_err" class="err_div"></div>
                <?php
            }
            ?>
            <textarea id = "text" name="text" placeholder="Ваш комментарий..." required ></textarea>
            <div id="text_err" class="err_div"></div>
            <div class="clear"></div>

            <input type="button" name="submit" class="submit" value="Отправить" onclick="return myAjax()">
        </div>
    </form>

    <?php

    while($row = $res->fetch_assoc()) {
        ?>
        <hr>
        <div class="comments">
            <div class="photo">
                <img alt="" src="/skins/img/default/users/100x100/
			<?php
                if (empty($row['avatar']))
                    echo 'noavatar.png';
                else
                    echo htmlspecialchars($row['avatar']);
                ?>">
            </div>
            <div class="field">
                <div class="login">
                    <?php echo htmlspecialchars($row['login']);?>
                    <br>
                    <span class="time">
                    Статус: <?php
                        if ($row['status'] == 0) echo 'гость сайта';
                        elseif ($row['status'] == 1) echo 'пользователь сайта';
                        elseif ($row['status'] == 5) echo 'администратор сайта';
                        else echo 'пользователь сайта (удален)';
                        ?>
                </span>
                </div>
                <?php
                if (isset(User::$data) && User::$role == 'admin') {
                    echo '<span id="text'.(int)$row['id'].'" contenteditable>'.nl2br(htmlspecialchars($row['text'])).'</span>';
                } else {
                    echo nl2br(htmlspecialchars($row['text']));
                }
                ?>
                <br>
                <span class="time">Опубликовано <?php echo htmlspecialchars($row['date']);?></span>
            </div>
            <?php
            if (isset(User::$data) && User::$role == 'admin' ) {
                echo '<a onclick="return confirm (\'Вы точно хотите удалить данный комментарий?\')"  title="Удалить комментарий" href="/comments?action=delete&id='.(int)$row['id'].'" class="delete"><i class="fa fa-trash-o fa-2x" aria-hidden="true"></i></a>';
                echo '<span onclick="return edit(this)" title="Редактировать комментарий" data-id="'.(int)$row['id'].'" class="edit"><i class="fa fa-pencil-square-o fa-2x" aria-hidden="true"></i></span>';
            }
            ?>
        </div>
        <?php
    }
    $res->close();
    ?>
    <div id="help">
        <img alt="" src="/skins/img/admin/info.png">
        <div class="info_text">
            <p>Редактирование комментариев</p>
            Для редактирования комментария, щелкните мышью по тексту комментария и внесите правки,
            после чего нажмите на данный знак редактирования.
        </div>
        <div class="clear"></div>
    </div>

</div>

<?php if (isset($info_name)) { ?>
    <div id="info_background"></div>
    <div id="info_text">
        <div class="info_header"> <?php echo $info_name;?></div>
        <div class="info_main">
            <img src="/skins/img/default/comments/<?php if($info_type == 'success') echo 'galochka.png'; else echo 'attantion.png';?>" alt="">
            <p><?php echo $info_text;?></p>
            <div id="info_close">OK</div>
        </div>
    </div>
    <?php
}
?>

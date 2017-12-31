<div class="main">
    <h2>БИТВА АЛКОГОЛИКОВ</h2>
    <form class="form<?php if (isset($user_shot) || isset($server_shot)) echo $form_background;?>" method="post">
        <div class="left text-center">
            <div class="progress-bar back-<?php echo hp_color($_SESSION['user_hp'])?>">
                <div class="progress <?php echo hp_color($_SESSION['user_hp'])?>" id="leftValue" data-leftValue="<?php echo $_SESSION['user_hp']*19.6;?>"></div>
            </div>
            <?php
            if (isset(User::$data)) {
                echo htmlspecialchars(User::$data['login']);
            } else {
                echo 'Гость';
            }
            ?>
            (<?php echo $_SESSION['user_hp'];?>/10)
        </div>
        <div class="right text-center">
            <div class="progress-bar back-<?php echo hp_color($_SESSION['server_hp'])?>">
                <div class="progress <?php echo hp_color($_SESSION['server_hp'])?> right" id="rightValue" data-rightValue="<?php echo $_SESSION['server_hp']*19.6;?>"></div>
            </div>
            Сервер (<?php echo $_SESSION['server_hp'];?>/10)
        </div>
        <div class="clear"></div>
        <hr>
        <div class="left user-enter-server-blocks text-center">
            ВАШЕ ЧИСЛО<br><br>
            <span><?php if(isset($not_errors)) echo htmlspecialchars($_POST['user_number']);?></span>
        </div>
        <div class="left user-enter-server-blocks">
            <input type="text" name="user_number" class="number <?php if(isset($_POST['user_number']) && !isset($not_errors) ) echo 'red-border';?>" placeholder="введите цифру от 1 до 3" autocomplete="off" autofocus>
            <input type="submit" name="submit" value="ОТПРАВИТЬ" class="submit">
        </div>
        <div class="left user-enter-server-blocks text-center">
            ЧИСЛО СЕРВЕРА<br><br>
            <span><?php if(isset($not_errors)) echo $server_number;?></span>
        </div>
        <?php
        if (isset($user_shot) || isset($server_shot)) {
            ?>
            <div class="clear"></div>
            <div class="text-center result <?php echo $result_color;?>"><?php echo $result_text;?></div>
        <?php } ?>
    </form>
</div>
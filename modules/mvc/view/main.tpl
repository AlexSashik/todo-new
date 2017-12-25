<div class="main">
    <h2>ФАЙЛОВЫЙ МЕНЕДЖЕР</h2>
    <?php
    if (!$is_root) {
        echo '<div><a href = "/mvc'.$double_point_path.'"><img src="/skins/img/default/mvc/folder.png" alt="">..</a></div>';
    }
    foreach ($folders as $k => $v) {
        echo '<div><a href = "/mvc'.$folder_paths[$k].'"><img src="/skins/img/default/mvc/folder.png" alt="">'.$v.'</a></div>';
    }
    foreach ($files as $k => $v) {
        echo '<div><img src="/skins/img/default/mvc/'.$files_exp[$k].'.png" alt="">'.$v.'</div>';
    }
    ?>
</div>
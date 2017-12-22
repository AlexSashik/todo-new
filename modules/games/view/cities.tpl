<div class="main">
    <h2>ИГРА В ГОРОДА</h2>
    <form action="" method="post" onsubmit="return false;">
        <div class="aside_div left">
            <div id="health_server" class="health">
                <i id="first-heart-server" class="fa fa-heart fa-2x" aria-hidden="true"></i>
                <i id="second-heart-server" class="fa fa-heart fa-2x" aria-hidden="true"></i>
                <i id="third-heart-server" class="fa fa-heart fa-2x" aria-hidden="true"></i>
            </div>
            <div class="first_line">
                <b>Сервер</b>
                <div id="spiner"><i class="fa fa-spinner fa-spin fa-lg fa-fw"></i></div>
            </div>
            <div id="server_cities">
                <!--Города сервера-->
            </div>
        </div>
        <div class="city_div left">
            <label for="city">Введите город</label>
            <input id="city" class="input_city" type="text" name="city" autofocus>
            <input class="submit" type="button" name="submit" value="ГОТОВО" onclick="return myAjax()" id="ready">
            <input class="submit" type="button" name="submit" value="ПРОПУСТИТЬ ХОД" onclick="return myAjax(true)" id="absence">
        </div>
        <div class="aside_div left">
            <div id="health_user" class="health">
                <i id="first-heart-user" class="fa fa-heart fa-2x" aria-hidden="true"></i>
                <i id="second-heart-user" class="fa fa-heart fa-2x" aria-hidden="true"></i>
                <i id="third-heart-user" class="fa fa-heart fa-2x" aria-hidden="true"></i>
            </div>
            <p class="first_line">
                <strong>
                    <?php
                    if (isset($_SESSION['user'])) echo htmlspecialchars($_SESSION['user']['login']); else echo 'Пользователь'
                    ?>
                </strong>
            </p>
            <div id="user_cities">
                <!--Города пользователя-->
            </div>
        </div>
    </form>
</div>
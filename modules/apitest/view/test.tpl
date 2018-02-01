<form action="" method="post" onsubmit="return false">
    <h2>API TEST</h2>
    <input class="lp" type="text" placeholder="Login" id="login">
    <input class="lp" type="password" placeholder="Password" id="pass">
    <div class="radio">
        <input class="format" name="format" id="json" type="radio" value="json" checked>
        <label for="json">JSON</label>
        <input class="format" id="xml" name="format" type="radio" value="xml">
        <label for="xml">XML</label>
    </div>

    <div class="buttons">
        <button onclick="return api('secret_key');">Получить secret_key</button>
        <button onclick="return api('view_social');">Какие соцсети</button>
        <hr>
        <div class="del_soc">
            <label for="fb"><i class="fa fa-facebook fa-lg"></i></label>
            <input id="fb" type="checkbox" value="facebook_id" name="delsoc[]">
            <label for="vk"><i class="fa fa-vk fa-lg"></i></label>
            <input id="vk" type="checkbox" value="vk_id" name="delsoc[]">
            <button onclick="return api('del_social');">Убрать соцсеть</button>
        </div>
    </div>
</form>

<div id="info_back"></div>
<div id="info_text">
    <div class="info_header"> Ответ сервера</div>
    <div class="info_main">
        <pre id="response"></pre>
        <div id="info_close">OK</div>
    </div>
</div>
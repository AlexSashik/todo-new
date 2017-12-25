<main>
    <div class="chat">
        <div class="chat-header">TodoChat</div>
        <div id="chat-body">
            <div id="chatSpace" class="main-chat" data-firstid="<?php
            if ($row = $res->fetch_assoc()) {
                echo $row['id'];
            } else {
                echo 0;
            } ?>">
                <span class="welcome">Добро пожаловать в чат!</span>
                <?php if ($row = $res->fetch_assoc()) echo $row['id'];?>
            </div>
            <form method="post" action="" onsubmit="return false;">
                <textarea placeholder="Отправить сообщение" id="text"></textarea>
                <div class="send">
                    <div class="settings" id="show-smiles">
                        <i class="fa fa-smile-o fa-lg" aria-hidden="true"></i>
                    </div>
                    <div id="users" class="settings" onclick="return usersList();">
                        <i class="fa fa-list fa-lg" aria-hidden="true"></i>
                    </div>
                    <button onclick="return mySend();">Чат</button>
                </div>
            </form>
            <div class="smiles">
                <div class="smile smile1" data-smile=":D"></div>
                <div class="smile smile2" data-smile=":''("></div>
                <div class="smile smile3" data-smile="^_^"></div>
                <div class="smile smile4" data-smile=":-*"></div>
                <div class="smile smile5" data-smile=">:-("></div>
                <div class="smile smile6" data-smile=":'("></div>
                <div class="smile smile7" data-smile=";)"></div>
                <div class="smile smile8" data-smile=":)"></div>
                <div class="smile smile9" data-smile="B)"></div>
            </div>
        </div>
        <div id="chat-list">
            <div class="chat-list-header">
                <i id="backToChat" class="fa fa-times fa-lg" aria-hidden="true" title="To chat"></i>
                <i id="refreshList" class="fa fa-refresh fa-lg" aria-hidden="true" title="Refresh"></i>
                Список участников
            </div>
            <div class="chat-list-main">
                <div id="loading">
                    <i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
                </div>
                <div class="group" id="admins">
                    МОДЕРАТОРЫ
                </div>
                <div class="group" id="online">
                    ОНЛАЙН
                </div>
                <div class="group" id="offline">
                    ОФФЛАЙН
                </div>
                <div class="group" id="ban">
                    ЗАБАНЕНЫ
                </div>
            </div>
        </div>
    </div>
</main>
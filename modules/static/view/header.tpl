<header class="white_background">
    <div class="header">
        <div id="first_layer">
            <a href="/"><img src="/skins/img/default/logo.png" alt="" id="logo"></a>
            <aside>
                <?php
                if (isset($_SESSION['user'])) {
                    echo '<p>Здравствуйте, <span>'.htmlspecialchars($_SESSION['user']['login']).'!</span></p>';
                    if ($_SESSION['user']['role'] === 'admin') {
                        echo '<a href="/admin" class="animate">Admin</a> ';
                    }
                    echo '<a href="/cab" class="animate">My profile</a>';
                    echo '<a href="/cab/exit" class="animate">Exit</a>';
                } else {
                    ?>
                    <a href="/cab" class="animate">Enter</a>
                    <a href="/cab/reg" class="animate">Registration</a>
                    <?php
                }
                ?>
                <i title="Search" id="search" class="fa fa-search fa-lg" aria-hidden="true"></i>
            </aside>
            <nav>
                <a href="/" class = "animate <?php if($_GET['_module'] == 'main') echo 'active';?>">HOME</a>
                <a href="/calculator" class="animate <?php  if($_GET['_module'] == 'calculator') echo 'active';?>">CALCULATOR</a>
                <a href="/games" id="game" class="animate <?php if($_GET['_module'] == 'games') echo 'active';?>">GAMES</a>
                <a href="/mvc" class="animate <?php if($_GET['_module'] == 'mvc') echo 'active';?>">MVC</a>
                <a href="/comments" class="animate <?php  if($_GET['_module'] == 'comments') echo 'active';?>">COMMENTS</a>
                <a href="/goods" class="animate <?php  if($_GET['_module'] == 'goods') echo 'active';?>">GOODS</a>
                <a href="/books" class="animate <?php  if($_GET['_module'] == 'books') echo 'active';?>">BOOKS</a>
                <a href="/chat" class="animate <?php  if($_GET['_module'] == 'chat') echo 'active';?>">CHAT</a>
                <a href="#" id="demo" class="animate" onclick="return false;">DEMO</a>
                <div id="game_nav">
                    <a href="/games/alcoholics" class="animate">buttle of alcoholics</a>
                    <a href="/games/cities" class="animate">the cities</a>
                </div>
                <div id="demo_nav">
                    <a href="/demo" target="_blank" class="animate">agrolux</a>
                    <a href="/vatel" target="_blank" class="animate">vatel</a>
                </div>
            </nav>
        </div>

        <div id="second_layer">
            <input type="text" placeholder="Enter keyword">
            <div class="close">
                <i class="fa fa-search fa-lg" aria-hidden="true"></i>|
                <i id="close" class="fa fa-times fa-lg" aria-hidden="true"></i>
            </div>
        </div>
    </div>
</header>
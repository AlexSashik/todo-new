<!DOCTYPE html>
<html lang="<?php echo Core::$LANGUAGE['html_locale']; ?>">
<head>
	<meta charset="UTF-8">
	<?php foreach(Core::$META['dns-prefetch'] as $v) { ?>
		<link rel="dns-prefetch" href="<?php echo $v; ?>">
	<?php } ?>
	<title><?php echo hc(Core::$META['title']); ?></title>
	<meta name="apple-mobile-web-app-title" content="<?php echo hc(Core::$META['title']); ?>">
	<meta name="description" content="<?php echo hc(Core::$META['description']); ?>">
	<meta name="keywords" content="<?php echo hc(Core::$META['keywords']); ?>">
	<meta name="author" content="Александр Константинов">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="format-detection" content="telephone=no">
	<meta name="format-detection" content="address=no">
	<meta name="robots" content="index, follow">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<?php if(Core::$LANGUAGE['status']) {foreach(Core::$LANGUAGE['allow'] as $v) { if($v != Core::$LANGUAGE['lang']) { ?>
		<link rel="alternate" hreflang="<?php echo $v; ?>" href="<?php echo createUrl('this',false,$v); ?>">
	<?php } } } ?>
	<?php if(!empty(Core::$META['prev'])) { ?>
		<link rel="prev" href="<?php echo Core::$META['prev']; ?>">
	<?php } ?>
	<?php if(!empty(Core::$META['next'])) { ?>
		<link rel="next" href="<?php echo Core::$META['next']; ?>">
	<?php } ?>
	<?php if(!empty(Core::$META['canonical'])) { ?>
		<link rel="canonical" href="<?php echo Core::$META['canonical']; ?>">
	<?php } ?>
	<?php if(!empty(Core::$META['shortlink'])) { ?>
		<link rel="shortlink" href="<?php echo Core::$META['shortlink']; ?>">
	<?php } ?>
	<?php echo Core::$META['head']; ?>
	<link rel="icon" href="/favicon.ico" type="image/x-icon">
	<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
	<link rel="apple-touch-icon" href="/touch-icon-iphone.png">
	<link rel="apple-touch-icon" sizes="76x76" href="/touch-icon-ipad.png">
	<link rel="apple-touch-icon" sizes="120x120" href="/touch-icon-iphone-retina.png">
	<link rel="apple-touch-icon" sizes="152x152" href="/touch-icon-ipad-retina.png">
	<link href="/skins/components/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/skins/components/bootstrap/bootstrap-theme.min.css" type = "text/css">
	<link href="/skins/css/font-awesome.min.css" rel="stylesheet">
	<link href="/skins<?php echo Core::$SKIN;?>/css/admin.min.css" rel="stylesheet">
	<script>
		var antixsrf = '<?php echo (isset($_SESSION['antixsrf']) ? $_SESSION['antixsrf'] : 'no'); ?>';
	</script>
	<!--[if lt IE 9]>
	<script src="/skins/components/bower/html5shiv/dist/html5shiv.min.js" defer></script>
	<script src="/skins/components/bower/respond/dest/respond.min.js" defer></script>
	<![endif]-->
	<script src="/skins/components/bower/jquery/dist/jquery.min.js"></script>
	<script src="/skins/components/bower/popper.js/dist/umd/popper.min.js"></script>
	<script src="/skins/components/bootstrap/bootstrap.min.js"></script>
	<script src="/vendor/schoolphp/library/Core/fw.min.js" defer></script>
</head>
<body>
<?php
if(isAdmin()) { /* Login */ ?>
    <header>
        <div class="container">
            <div class="row">
                <div class="col-xs-4 col-sm-6 col-md-6 col-lg-6">
                    <a href="/admin" title="На главную" data-toggle="tooltip" data-placement="bottom"><img alt="" src="/skins/img/admin/short-logo.png"></a>
                </div>
                <div class="col-xs-8 col-sm-6 col-md-6 col-lg-6 text-primary text-right salut">
                    <div>Здравствуйте, <?php echo htmlspecialchars(User::$data['login'])?>!</div>
                    <a class="btn btn-success btn-adapt" href="<?php
                    if($_GET['_module'] == 'goods')
                        echo '/goods';
                    elseif ($_GET['_module'] == 'books')
                        echo '/books';
                    elseif($_GET['_module'] == 'authors')
                        echo '/books/authors';
                    else
                        echo '/';
                    ?>" target="_blank">
                        <i class="glyphicon glyphicon-eye-open"></i>
                        <span class="large">Просмотр сайта</span>
                        <span class="small">На сайт</span>
                    </a>
                    <a class="btn btn-danger btn-adapt" href="/" title="Выйти из админки" data-toggle="tooltip" data-placement="bottom">
                        <i class="glyphicon glyphicon-log-in"></i>
                        Exit
                    </a>
                </div>
            </div>
        </div>

        <nav class="navbar navbar-default">
            <div class="container">
                <div class="navbar-header">
                    <button class="navbar-toggle" data-toggle="collapse" data-target="#mainNav">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>

                <div class="collapse navbar-collapse" id="mainNav">
                    <ul class="nav navbar-nav">
                        <li class="<?php if ($_GET['_module'] == 'main') echo 'active';?>"">
                        <a href="/admin">
                            <i class="glyphicon glyphicon-home"></i>
                            Главная
                        </a>
                        </li>
                        <li class="<?php if ($_GET['_module'] == 'goods') echo 'active';?>">
                            <a href="/admin/goods">
                                <i class="glyphicon glyphicon-shopping-cart"></i>
                                Товары
                            </a>
                        </li>
                        <li class="<?php if ($_GET['_module'] == 'users') echo 'active';?>">
                            <a href="/admin/users">
                                <i class="glyphicon glyphicon-user"></i>
                                Пользователи
                            </a>
                        </li>
                        <li class="<?php if ($_GET['_module'] == 'books') echo 'active';?>">
                            <a href="/admin/books">
                                <i class="glyphicon glyphicon-book"></i>
                                Книги
                            </a>
                        </li>
                        <li class="<?php if ($_GET['_module'] == 'authors') echo 'active';?>">
                            <a href="/admin/authors">
                                <i class="glyphicon glyphicon-pencil"></i>
                                Авторы
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <div id="upward" class = "animate"></div>
<?php } ?>
<?php echo $content; ?>
    <footer class="text-center">
        Copyrights <?php echo date('Y');?>. Admin for ItIdeas. All Rights Reserved.
    </footer>
    <script src="/skins<?php echo Core::$SKIN;?>/js/admin/script.js"></script>
    <script>
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip();
        })
    </script>
    <?php echo \Core::$END; ?>
</body>
</html>
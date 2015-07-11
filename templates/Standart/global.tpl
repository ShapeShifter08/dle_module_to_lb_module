<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
<head>
	<meta http-equiv="content-type" content="text/html;charset={charset}" />
	<title>{meta_title}</title>
	<meta http-equiv="content-language" content="ru" />
	<meta name="description" content="{meta_description}" />
	<meta name="keywords" content="{meta_keyword}" />
	<meta name="robots" content="all" />
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE9"/>
    [minify_on]
	<link rel="stylesheet" type="text/css" href="{HOME_LINK}components/scripts/min/index.php?charset={charset}&amp;f=templates/{TEMPLATE_NAME}/css/style.css&amp;v={FORUM_CSSJS}" media="all" />
	<link rel="stylesheet" type="text/css" href="{HOME_LINK}components/scripts/min/index.php?charset=UTF-8&amp;f=templates/{TEMPLATE_NAME}/css/syntax/shCoreEclipse.css&amp;v={FORUM_CSSJS}" media="all" />
    <link rel="stylesheet" type="text/css" href="{HOME_LINK}components/scripts/min/index.php?charset={charset}&amp;f=templates/{TEMPLATE_NAME}/css/bootstrap.css&amp;v={FORUM_CSSJS}" media="all" />
    <!--[if lt IE 9]><link rel="stylesheet" type="text/css" href="{HOME_LINK}components/scripts/min/index.php?charset={charset}&amp;f=templates/{TEMPLATE_NAME}/css/ie.css" media="all" /><![endif]-->
    [/minify_on] 
    [minify_off]
	<link rel="stylesheet" type="text/css" href="{TEMPLATE}/css/style.css?v={FORUM_CSSJS}" media="all" />
	<link rel="stylesheet" type="text/css" href="{TEMPLATE}/css/syntax/shCoreEclipse.css?v={FORUM_CSSJS}" media="all" />
    <link rel="stylesheet" type="text/css" href="{TEMPLATE}/css/bootstrap.css?v={FORUM_CSSJS}" media="all" />
    <!--[if lt IE 9]><link rel="stylesheet" type="text/css" href="{TEMPLATE}/css/ie.css?v={FORUM_CSSJS}" media="all" /><![endif]-->
    [/minify_off] 

    {SCRIPTS}

    {SCRIPTS_FILE}
    
    [minify_on]
    <script type="text/javascript" src="{HOME_LINK}components/scripts/min/index.php?charset={charset}&amp;b=templates/{TEMPLATE_NAME}/js&amp;f=project.js,placehol.js,bootstrap-tooltip.js&amp;v={FORUM_CSSJS}" charset='{charset}'></script>
    <!--[if IE 6]><script type="text/javascript" src="{HOME_LINK}components/scripts/min/index.php?charset={charset}&amp;f=templates/{TEMPLATE_NAME}/js/project_ie6.js&amp;v={FORUM_CSSJS}"></script><![endif]-->
    [/minify_on]
    [minify_off]
    <script type="text/javascript" src="{TEMPLATE}/js/project.js?v={FORUM_CSSJS}" charset='{charset}'></script>
    <script type="text/javascript" src="{TEMPLATE}/js/placehol.js?v={FORUM_CSSJS}" charset='{charset}'></script>
    <script type="text/javascript" src="{TEMPLATE}/js/bootstrap-tooltip.js?v={FORUM_CSSJS}" charset='{charset}'></script>
    <!--[if IE 6]><script type="text/javascript" src="{TEMPLATE}/js/project_ie6.js?v={FORUM_CSSJS}"></script><![endif]-->
    [/minify_off]
</head>
<body>
{AJAX_CONTENT}

<div class="header">
	<h1><a href="{HOME_LINK}" title="Logic board"><span>Logic</span> board<i></i></a></h1>

	<ol id="main_nav">
		<li><a href="{DLE_LINK}"><span>Сайт</span></a></li>
		<li><a href="{HOME_LINK}"><span>LogicBoard</span></a></li>
		<li [module_board]class="mn_active"[/module_board]><a href="{HOME_LINK}"><span>Обсуждения</span></a></li>
		<li [module_users]class="mn_active"[/module_users]><a href="{link_users}"><span>Пользователи</span></a></li>
        [global_group=5]<li><a href="/?do=register"><span>Регистрация</span></a></li>[/global_group]
	</ol>

    {login}
    	
	<form id="search" name="search" action="{HOME_LINK}?do=search" method="post">
		<fieldset>
			<input type="search" placeholder="поиск по форуму" name="w" id="ul_find" />
			<input type="submit" name="do_search" value="поиск" title="поиск" />
			<span>[module_board]Обсуждения[/module_board][module_users]Пользователи[/module_users]<i></i></span>
			<ul>
				<li><strong>Искать в:</strong></li>
				<li><label><input type="radio" name="ms" value="0" [module_board]checked="checked"[/module_board] />Обсуждения</label></li>
				<li><label><input type="radio" name="ms" value="1" [module_users]checked="checked"[/module_users] />Пользователи</label></li>
			</ul>
		</fieldset>
	</form>
	
	<ol id="h_serv">
		<li id="hs_vk"><a href="#" title="ВКонтакте">ВКонтакте</a></li>
		<li id="hs_fb"><a href="#" title="Facebook">Facebook</a></li>
	</ol>
	
	<div class="h_info cle">
		<ol itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
			<li>{speedbar}</li>
		</ol>
		<a href="{link_rss}" class="h_rss">Новые публикации<i></i></a>
		<a href="#" id="tog_sidebar"><span class="ts_active">Скрыть панель справа</span><span>Показать панель справа</span><i></i></a>
	</div>	
</div>
<!--header end-->
<div class="center">

	<div id="board_index" class="cle">
		<div class="categories">
			<div class="categories_in">
                {mysql_stat}    
                [last_forum_news]
                <div class="autoriz" style="margin: 0 0 7px;" id="last_news_box">
                <ol>
                    <li><b>Последние новости:</b> <a href="{last_forum_news_topic_link}">{last_forum_news_title}</a> <a href="{last_forum_news_close}" onclick="Last_News_Close('{time_close}');return false;"><font class="smalltext">(закрыть)</font></a></li>
                </ol>
                <div class="co co5"><div class="tl"></div><div class="tr"></div><div class="bl"></div><div class="br"></div></div>
                </div>
                [/last_forum_news]           
                {message}
                {content}
                {templates}
            </div>
		</div>
		<!--categories end-->
            
		<div class="board_side">
			<div class="board_block">
				<h3 id="c_01">Популярные статьи<span title="свернуть" class="c_toggle"></span><i></i><span class="co co5"><span class="tr"></span><span class="br"></span></span></h3>
				<div class="bb_cont">
					<ol class="bb_last_feed">
						{topnews}				
					</ol>
					<div class="co co5"><div class="tl"></div><div class="tr"></div><div class="bl"></div><div class="br"></div></div>
				</div>
			</div>
			<!--board_block end-->
			<div class="board_block">
				<h3 id="c_01">Последние темы<span title="свернуть" class="c_toggle"></span><i></i><span class="co co5"><span class="tr"></span><span class="br"></span></span></h3>
				<div class="bb_cont">
					<ol class="bb_last_feed">
						{last_topics}				
					</ol>
					<div class="co co5"><div class="tl"></div><div class="tr"></div><div class="bl"></div><div class="br"></div></div>
				</div>
			</div>
			<!--board_block end-->

			<div class="board_block">
				<h3 id="c_05">Изменения статуса<span title="свернуть" class="c_toggle"></span><i></i><span class="co co5"><span class="tr"></span><span class="br"></span></span></h3>
				<div class="bb_cont">
					<ol class="bb_status_ch">
                        {last_status}
					</ol>
					<div class="co co5"><div class="tl"></div><div class="tr"></div><div class="bl"></div><div class="br"></div></div>
				</div>
			</div>
			<!--board_block end-->

			<div class="board_block">
				<h3 id="c_05">Статьи<span title="свернуть" class="c_toggle"></span><i></i><span class="co co5"><span class="tr"></span><span class="br"></span></span></h3>
				<div class="bb_cont">
					<ol class="bb_status_ch">
                        {dle_news}
					</ol>
					<div class="co co5"><div class="tl"></div><div class="tr"></div><div class="bl"></div><div class="br"></div></div>
				</div>
			</div>
			<!--board_block end-->
            
		</div>
		<!--board_side end-->
	</div>
	<!--board_index end-->

	<div id="stat_links" class="cor5">
		<ol>
			<li><a href="{link_topic_active}" id="123123">Активные темы</a></li>
			<li><a href="{link_moderators}">Администрация</a></li>
            <li><a href="{link_last_topics}">Последние темы</a></li>
            <li><a href="{link_last_posts}">Последние ответы</a></li>
		</ol>
		<div class="co co5"><div class="tl"></div><div class="tr"></div><div class="bl"></div><div class="br"></div></div>
	</div>
	<!--stat_links end-->

    {statistic}
	<!--statistics end-->
</div>
<!--center end-->
	
<div class="footer">
	<div id="f_menu" class="cle cor5">
		<ol>
			<li><a href="{DLE_LINK}">Сайт</a></li>
			<li><a href="{HOME_LINK}">LogicBoard</a></li>
			<li><a href="{HOME_LINK}">Обсуждения</a></li>
			<li><a href="{link_users}">Пользователи</a></li>
            <li><a href="{link_feedback}">Обратная связь</a></li>
		</ol>
		<strong>Сейчас: {time_now} </strong>
		<div class="co co5"><div class="tl"></div><div class="tr"></div><div class="bl"></div><div class="br"></div></div>
	</div>
	<div id="f_copy">
		&copy; 2011-{thisyear} {copyright}
		<noindex>
		<ol>
			<li><a href="{HOME_LINK}?do=rules">Правила форума</a></li>
            <li><a href="{clear_cookie}">Очистить Cookies</a></li>
            [global_not_group=5]<li><a href="{all_tf_read}">Отметить все темы и форумы прочитанными</a></li>[/global_not_group]
		</ol>
		</noindex>
	</div>
    
</div>
<!--footer end-->
	
<script type="text/javascript">
	inputPlaceholder(document.getElementById('ul_login'))
	inputPlaceholder(document.getElementById('ul_pass'))
	inputPlaceholder(document.getElementById('ul_find'))
    inputPlaceholder(document.getElementById('ul_inputsearch'))
</script>
	
</body>
</html>
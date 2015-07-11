<?php
/*
=====================================================
 DataLife Engine - by SoftNews Media Group 
-----------------------------------------------------
 http://dle-news.ru/
-----------------------------------------------------
 Copyright (c) 2004,2014 SoftNews Media Group
=====================================================
 Данный код защищен авторскими правами
=====================================================
 Файл: topnews.php
-----------------------------------------------------
 Назначение: вывод рейтинговых статей
=====================================================
*/

if (! defined ( 'LogicBoard' ))
{
	@include '../logs/save_log.php';
	exit ( "Error, wrong way to file.<br><a href=\"/\">Go to main</a>." );
}

// Определение категорий и их параметры
$cache_dle_cat_info = $cache->take("cat_info", "", "dle_modules");

if (!is_array($cache_dle_cat_info))
{
    $cache_dle_cat_info = array ();
   	
    ############ SQL - Start
    $DB->prefix = DLE_USER_PREFIX;
    $DB->rows(array("id", "parentid", "posi", "name", "alt_name", "news_number")); 
    $DB->table("category");
    $DB->sort("posi ASC");
    $DB->select();
    ############ SQL - End

   	while ( $row = $DB->get_row () )
    {		
  		$cache_dle_cat_info[$row['id']] = array ();
  		foreach ( $row as $key => $value )
        {
 			$cache_dle_cat_info[$row['id']][$key] = stripslashes($value);
  		}
   	}

    $cache->take("cat_info", $cache_dle_cat_info, "dle_modules");
   	$DB->free();
}

$this_month = date( 'Y-m-d H:i:s', $_TIME );

$tpl->load_template( 'topnews.tpl' );

$top_number = 10;
$category_separator = ","; // Задачем разделитель как в настройках DLE

$db->query( "SELECT p.id, p.date, p.short_story, p.xfields, p.title, p.category, p.alt_name FROM " . PREFIX . "_post p LEFT JOIN " . PREFIX . "_post_extras e ON (p.id=e.news_id) WHERE p.approve=1 ORDER BY rating DESC, comm_num DESC, news_read DESC, date DESC LIMIT 0,{$top_number}" );

while ( $row = $db->get_row() )
{
	$row['date'] = strtotime( $row['date'] );

	if( ! $row['category'] )
	{
		$my_cat = "---";
		$my_cat_link = "---";
	}
	else
	{
		$my_cat = array();
		$my_cat_link = array();
		$cat_list = explode( ',', $row['category'] );

		if ($category_separator != ',') $category_separator = ' '.$category_separator;
	 
		if( count( $cat_list ) == 1 )
		{
			$my_cat[] = $cache_dle_cat_info[$cat_list[0]]['name'];
			$my_cat_link = get_categories( $cat_list[0], $category_separator );
		}
		else
		{
			foreach ( $cat_list as $element )
			{
				if( $element )
				{
					$my_cat[] = $cache_dle_cat_info[$element]['name'];
					if( Links::$rewrite_url_site )
					{
						$my_cat_link[] = "<a href=\"" . Links::$main_site . get_dle_url( $element ) . "/\">{$cache_dle_cat_info[$element]['name']}</a>";
					}
					else
					{
						$my_cat_link[] = "<a href=\"".Links::$main_site."?do=cat&category={$cache_dle_cat_info[$element]['alt_name']}\">{$cache_dle_cat_info[$element]['name']}</a>";
					}
				}
			}
			
			$my_cat_link = implode( "{$category_separator} ", $my_cat_link );
		}
		
		$my_cat = implode( "{$category_separator} ", $my_cat );
	}

	$row['category'] = intval( $row['category'] );
	
	if( Links::$rewrite_url_site )
	{
		if( $cache_config['dle_seo_type']['conf_value'] == 1 OR $cache_config['dle_seo_type']['conf_value'] == 2 )
		{
			if( $row['category'] and $cache_config['dle_seo_type']['conf_value'] == 2 )
			{
				$full_link = Links::$main_site . get_dle_url( $row['category'] ) . "/" . $row['id'] . "-" . $row['alt_name'] . ".html";
			}
			else
			{
				$full_link = Links::$main_site . $row['id'] . "-" . $row['alt_name'] . ".html";
			}
		}
		else
		{
			$full_link = Links::$main_site . date( 'Y/m/d/', $row['date'] ) . $row['alt_name'] . ".html";
		}
	}
	else
	{
		$full_link = Links::$main_site . "index.php?newsid=" . $row['id'];
	}
		
	$tpl->set( '{date}', formatdate($row['date']) );

	$news_date = $row['date'];
	$tpl->copy_template = preg_replace_callback ( "#\{date=(.+?)\}#i", "formdate", $tpl->copy_template );

	$tpl->set( '{category}', $my_cat );
	$tpl->set( '{link-category}', $my_cat_link );

	$row['title'] = stripslashes( $row['title'] );

	$row['title'] = str_replace( "{", "&#123;", $row['title'] );

	$tpl->set( '{title}', $row['title'] );
	
	if ( preg_match( "#\\{title limit=['\"](.+?)['\"]\\}#i", $tpl->copy_template, $matches ) )
	{
		$count= intval($matches[1]);

		$row['title'] = strip_tags( $row['title'] );

		if( $count AND utf8_strlen($row['title']) > $count )
		{
			$row['title'] = utf8_substr($row['title'], 0, $count);
				
			if(($temp_dmax = utf8_strrpos($row['title'], ' ')))
			{
				$row['title'] = utf8_substr($row['title'], 0, $temp_dmax). " ...";
			}
		}

		$tpl->set( $matches[0], $row['title'] );
	}

	$tpl->set( '{link}', $full_link );

	$row['short_story'] = stripslashes( $row['short_story'] );

	// Доступ к скрытому тексту из настроек форуме (!не DLE)
	if(Member::Group_Cache("g_hide_text"))
	{
		$row['short_story'] = str_ireplace( "[hide]", "", str_ireplace( "[/hide]", "", $row['short_story']) );
	}
	else
	{
		$row['short_story'] = preg_replace ( "#\[hide\](.+?)\[/hide\]#ims", "<div class=\"quote\">Внимание! У Вас нет прав для просмотра скрытого текста.</div>", $row['short_story'] );
	}

	if (stripos ( $tpl->copy_template, "{image-" ) !== false)
	{
		$images = array();

		preg_match_all('/(img|src)=("|\')[^"\'>]+/i', $row['short_story'], $media);
		$data = preg_replace('/(img|src)("|\'|="|=\')(.*)/i',"$3",$media[0]);

		foreach($data as $url)
		{
			$info = pathinfo($url);

			if (isset($info['extension']))
			{
				if ($info['filename'] == "spoiler-plus" OR $info['filename'] == "spoiler-plus" ) continue;

				$info['extension'] = strtolower($info['extension']);

				if (($info['extension'] == 'jpg') || ($info['extension'] == 'jpeg') || ($info['extension'] == 'gif') || ($info['extension'] == 'png'))
				{
					array_push($images, $url);
				}
			}
		}

		if (count($images))
		{
			$i=0;

			foreach($images as $url)
			{
				$i++;
				$tpl->copy_template = str_replace( '{image-'.$i.'}', $url, $tpl->copy_template );
				$tpl->copy_template = str_replace( '[image-'.$i.']', "", $tpl->copy_template );
				$tpl->copy_template = str_replace( '[/image-'.$i.']', "", $tpl->copy_template );
			}

		}

		$tpl->copy_template = preg_replace( "#\[image-(.+?)\](.+?)\[/image-(.+?)\]#is", "", $tpl->copy_template );
		$tpl->copy_template = preg_replace( "#\\{image-(.+?)\\}#i", "{THEME}/dleimages/no_image.jpg", $tpl->copy_template );
	}

	$tpl->set( '{text}', $row['short_story'] );

	if ( preg_match( "#\\{text limit=['\"](.+?)['\"]\\}#i", $tpl->copy_template, $matches ) )
	{
		$count= intval($matches[1]);

		$row['short_story'] = str_replace( "</p><p>", " ", $row['short_story'] );
		$row['short_story'] = strip_tags( $row['short_story'], "<br>" );
		$row['short_story'] = trim(str_replace( "<br>", " ", str_replace( "<br />", " ", str_replace( "\n", " ", str_replace( "\r", "", $row['short_story'] ) ) ) ));

		if( $count AND utf8_strlen($row['short_story']) > $count )
		{
			$row['short_story'] = utf8_substr($row['short_story'], 0, $count);
				
			if(($temp_dmax = utf8_strrpos($row['short_story'], ' ')))
			{
				$row['short_story'] = utf8_substr($row['short_story'], 0, $temp_dmax);
			}
		}

		$tpl->set( $matches[0], $row['short_story'] );
	}

	$tpl->compile( 'topnews' );
}

$tpl->clear();	
$db->free();
?>
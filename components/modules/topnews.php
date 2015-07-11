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

$this_month = date( 'Y-m-d H:i:s', $_TIME );

$tpl->load_template( 'topnews.tpl' );

$top_number = 10;

$db->query( "SELECT p.id, p.date, p.short_story, p.xfields, p.title, p.category, p.alt_name FROM " . PREFIX . "_post p LEFT JOIN " . PREFIX . "_post_extras e ON (p.id=e.news_id) WHERE p.approve=1 ORDER BY rating DESC, comm_num DESC, news_read DESC, date DESC LIMIT 0,{$top_number}" );

while ( $row = $db->get_row() ) {
	
	$row['date'] = strtotime( $row['date'] );

	if( ! $row['category'] ) {
		$my_cat = "---";
		$my_cat_link = "---";
	} else {
		
		$my_cat = array ();
		$my_cat_link = array ();
		$cat_list = explode( ',', $row['category'] );

		if ($config['category_separator'] != ',') $config['category_separator'] = ' '.$config['category_separator'];
	 
		if( count( $cat_list ) == 1 ) {
			
			$my_cat[] = $cat_info[$cat_list[0]]['name'];
			
			$my_cat_link = get_categories( $cat_list[0], $config['category_separator'] );
		
		} else {
			
			foreach ( $cat_list as $element ) {
				if( $element ) {
					$my_cat[] = $cat_info[$element]['name'];
					if( $config['allow_alt_url'] ) $my_cat_link[] = "<a href=\"" . $config['http_home_url'] . get_url( $element ) . "/\">{$cat_info[$element]['name']}</a>";
					else $my_cat_link[] = "<a href=\"$PHP_SELF?do=cat&category={$cat_info[$element]['alt_name']}\">{$cat_info[$element]['name']}</a>";
				}
			}
			
			$my_cat_link = implode( "{$config['category_separator']} ", $my_cat_link );
		}
		
		$my_cat = implode( "{$config['category_separator']} ", $my_cat );
	}

	$row['category'] = intval( $row['category'] );
	
	if( $config['allow_alt_url'] ) {
		
		if( $config['seo_type'] == 1 OR $config['seo_type'] == 2 ) {
			
			if( $row['category'] and $config['seo_type'] == 2 ) {
				
				$full_link = $config['http_home_url'] . get_url( $row['category'] ) . "/" . $row['id'] . "-" . $row['alt_name'] . ".html";
			
			} else {
				
				$full_link = $config['http_home_url'] . $row['id'] . "-" . $row['alt_name'] . ".html";
			
			}
		
		} else {
			
			$full_link = $config['http_home_url'] . date( 'Y/m/d/', $row['date'] ) . $row['alt_name'] . ".html";
		}
	
	} else {
		
		$full_link = $config['http_home_url'] . "index.php?newsid=" . $row['id'];
	
	}

	if( date( 'Ymd', $row['date'] ) == date( 'Ymd', $_TIME ) ) {
		
		$tpl->set( '{date}', $lang['time_heute'] . langdate( ", H:i", $row['date'] ) );
	
	} elseif( date( 'Ymd', $row['date'] ) == date( 'Ymd', ($_TIME - 86400) ) ) {
		
		$tpl->set( '{date}', $lang['time_gestern'] . langdate( ", H:i", $row['date'] ) );
	
	} else {
		
		$tpl->set( '{date}', langdate( $config['timestamp_active'], $row['date'] ) );
	
	}

	$news_date = $row['date'];
	$tpl->copy_template = preg_replace_callback ( "#\{date=(.+?)\}#i", "formdate", $tpl->copy_template );

	$tpl->set( '{category}', $my_cat );
	$tpl->set( '{link-category}', $my_cat_link );

	$row['title'] = stripslashes( $row['title'] );

	$row['title'] = str_replace( "{", "&#123;", $row['title'] );

	$tpl->set( '{title}', $row['title'] );
	
	if ( preg_match( "#\\{title limit=['\"](.+?)['\"]\\}#i", $tpl->copy_template, $matches ) ) {
		$count= intval($matches[1]);

		$row['title'] = strip_tags( $row['title'] );

		if( $count AND dle_strlen( $row['title'], $config['charset'] ) > $count ) {
				
			$row['title'] = dle_substr( $row['title'], 0, $count, $config['charset'] );
				
			if( ($temp_dmax = dle_strrpos( $row['title'], ' ', $config['charset'] )) ) $row['title'] = dle_substr( $row['title'], 0, $temp_dmax, $config['charset'] ). " ...";
			
		}

		$tpl->set( $matches[0], $row['title'] );

	}


	$tpl->set( '{link}', $full_link );

	$row['short_story'] = stripslashes( $row['short_story'] );

	if( $user_group[$member_id['user_group']]['allow_hide'] ) $row['short_story'] = str_ireplace( "[hide]", "", str_ireplace( "[/hide]", "", $row['short_story']) );
	else $row['short_story'] = preg_replace ( "#\[hide\](.+?)\[/hide\]#ims", "<div class=\"quote\">" . $lang['news_regus'] . "</div>", $row['short_story'] );

	if (stripos ( $tpl->copy_template, "{image-" ) !== false) {

		$images = array();
		preg_match_all('/(img|src)=("|\')[^"\'>]+/i', $row['short_story'], $media);
		$data=preg_replace('/(img|src)("|\'|="|=\')(.*)/i',"$3",$media[0]);

		foreach($data as $url) {
			$info = pathinfo($url);
			if (isset($info['extension'])) {
				if ($info['filename'] == "spoiler-plus" OR $info['filename'] == "spoiler-plus" ) continue;
				$info['extension'] = strtolower($info['extension']);
				if (($info['extension'] == 'jpg') || ($info['extension'] == 'jpeg') || ($info['extension'] == 'gif') || ($info['extension'] == 'png')) array_push($images, $url);
			}
		}

		if ( count($images) ) {
			$i=0;
			foreach($images as $url) {
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

	if ( preg_match( "#\\{text limit=['\"](.+?)['\"]\\}#i", $tpl->copy_template, $matches ) ) {
		$count= intval($matches[1]);

		$row['short_story'] = str_replace( "</p><p>", " ", $row['short_story'] );
		$row['short_story'] = strip_tags( $row['short_story'], "<br>" );
		$row['short_story'] = trim(str_replace( "<br>", " ", str_replace( "<br />", " ", str_replace( "\n", " ", str_replace( "\r", "", $row['short_story'] ) ) ) ));

		if( $count AND dle_strlen( $row['short_story'], $config['charset'] ) > $count ) {
				
			$row['short_story'] = dle_substr( $row['short_story'], 0, $count, $config['charset'] );
				
			if( ($temp_dmax = dle_strrpos( $row['short_story'], ' ', $config['charset'] )) ) $row['short_story'] = dle_substr( $row['short_story'], 0, $temp_dmax, $config['charset'] );
			
		}

		$tpl->set( $matches[0], $row['short_story'] );

	}

	$tpl->compile( 'topnews' );
}

$tpl->clear();	
$db->free();
?>
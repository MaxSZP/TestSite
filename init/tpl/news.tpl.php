<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 padding-left-none padding-right-none">
	
	<?php
	// Количество символов текста новости, выводимых в новостях
	$maxNewsLenghText = 300; 
	
	if (isset($params['newsId'])){
		
		$newsMain = $db->getNewsId($params['newsId']);
		if($newsMain){
			$imgFilePath = "";
			if( ($newsMain[0]['img_news'] != "") && is_file(ROOT_DIR.Config::$newsImg.$newsMain[0]['img_news']) ){
				$imgFilePath = '<img class = "news-img" src="'.ROOT.Config::$newsImg.$newsMain[0]['img_news'].'" width="'.Config::$widthNews.'" height="'.Config::$heightNews.'" align="left" alt="News">';
			}
	?>
		<div class="news-full blog-content margin-bottom-10 padding-top-10 scroll_effect fadeInLeft">
			<ul class="margin-top-10 margin-bottom-15"> <li class="fa fa-calendar"><?=date('d-m-Y', $newsMain[0]['data'])?></li> </ul>
			<div class="blog-title"> <a href = "<?=ROOT?>page/view/name/index/newsId/<?=$newsMain[0]['id']?>" ><strong class="margin-top-5 margin-bottom-15"><?=$newsMain[0]['title']?></strong> </a></div>
			<div class="post-entry clearfix"><?=$imgFilePath?><?=$newsMain[0]['text']?></div>
		</div>
    <div class="news-full-buttom"></div>
	<?php
		}
	}
	
	
	$num = 1;
	if(isset($params['num']) && (int)$params['num'] > 0){
		$num = (int)$params['num'];
	}
	$news = $db->getNews($num);
	
	
	foreach($news as $key){
		$textNews = $key['text'];
		if(strlen($textNews) > $maxNewsLenghText){
			if(strpos($textNews, " ", $maxNewsLenghText)){
				$textNews = substr($textNews, 0, strpos($textNews, " ", $maxNewsLenghText));
			}
		}
		
	?>
		<div class="blog-content margin-bottom-10 padding-top-10 scroll_effect fadeInLeft">
		
		<ul class="margin-top-10 margin-bottom-15"> <li class="fa fa-calendar"><?=date('d-m-Y', $key['data'])?></li> </ul>
		<div class="blog-title"> <a href = "<?=ROOT?>page/view/name/<?=$params['name']?>/newsId/<?=$key['id']?>" ><strong class="margin-top-5 margin-bottom-15"><?=$key['title']?></strong> </a></div>
		<div class="post-entry clearfix"> <p><?=$textNews?><a href = "<?=ROOT?>page/view/name/<?=$params['name']?>/newsId/<?=$key['id']?>" >...</a></p> </div>
		
		</div>
<?php	} 
	unset($key);
	?>
	
	
<?php
  // Блок пагинатора
	//Общее количество новостей для вывода
	$count = $db->getNewsCount(1);
	
	if($count > $_SESSION['maxNews']){
    $_start_item = ($num-1)*$_SESSION['maxNews'] + 1;
    $_end_item = ($num*$_SESSION['maxNews'] <= $count) ? $num*$_SESSION['maxNews'] : $count ;
?>
  <div class="pagination">
    <div class="item-total"> Показано <?=$_start_item?> по <?=$_end_item?> из <?=$count?> </div>
    <ul>
<?php
			for($i = 1; ($i-1)*$_SESSION['maxNews'] < $count; $i++){
			$class = ($i == 1) ? "first-bg-hover " : "" ;
      $class = ($i == $num) ? $class."active" : $class ;
			$urlPage = ROOT . "page/view/name/" . $params['name'] . "/num/" . $i . "/" ;
?>
      <li class="<?=$class?>"><a href="<?=$urlPage?>"><?=$i?></a></li>
<?php
		}
?>
		</ul>
	</div>
<?php
	}
?>

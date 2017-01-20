<?php session_start();
define('INCLUDE_CHECK',true);
$directory = "index";
$catalog=0;
$u = "1";
include("init/init_base.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $row_contact['title']," - "; if(strlen($row_keywords['title'])>0) { echo $row_keywords['title']; } else { echo $row_page['title'];}?></title>
<?php if ($totalRows_keywords>0) { 
if (strlen($row_keywords['keywords'])>0) { ?>
<meta name="Keywords" content="<?php echo $row_keywords['keywords'];?>" />
<?php } if (strlen($row_keywords['description'])>0) { ?>
<meta name="Description" content="<?php echo $row_keywords['description'];?>" />
<?php }} ?>
<link rel="icon" href="<?php echo SITE_URL; ?>images/icon.ico" type="image/x-icon" />
<link rel="shortcut icon" href="<?php echo SITE_URL; ?>images/icon.ico" type="image/x-icon" />
<link type="text/css" title="screen style" rel="stylesheet" href="<?php echo SITE_URL; ?>css/thrColFixHdr.css"/>
<link rel="stylesheet" href="<?php echo SITE_URL; ?>menu39/styles.css" type="text/css" />
<script src="<?php echo SITE_URL; ?>javascripts/Scripts/swfobject_modified.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo SITE_URL; ?>javascripts/jquery.js"></script>
<script type="text/javascript" src="<?php echo SITE_URL; ?>javascripts/scroll.js"></script>
<script type="text/javascript" src="<?php echo SITE_URL; ?>javascripts/behavior.js"></script>
<script type="text/javascript" src="<?php echo SITE_URL; ?>javascripts/rating.js"></script>
<script type="text/javascript" src="<?php echo SITE_URL; ?>javascripts/facebox/facebox.js"></script>
<link rel="stylesheet" href="<?php echo SITE_URL; ?>css/scroll.css" type="text/css" />
<link rel="stylesheet" href="<?php echo SITE_URL; ?>css/rating.css" type="text/css" />
<link rel="stylesheet" href="<?php echo SITE_URL; ?>highslide/highslide.css" type="text/css" />
<link href="<?php echo SITE_URL; ?>javascripts/facebox/facebox.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo SITE_URL; ?>highslide/highslide-with-gallery.js"></script>
<script type="text/javascript">
jQuery(document).ready(function($) {
$('a[rel*=facebox]').facebox({
loading_image : '<?php echo SITE_URL; ?>javascripts/facebox/loading.gif',
close_image   : '<?php echo SITE_URL; ?>javascripts/facebox/closelabel.gif'
}) 

$('#s2 img').click(function (){
      document.location.href = $(this).attr('rel');
	  }).css('cursor', 'pointer');
});
</script>
<script type="text/javascript">
hs.graphicsDir = '<?php echo SITE_URL; ?>highslide/graphics/';
hs.align = 'center';
hs.transitions = ['expand', 'crossfade'];
hs.outlineType = 'rounded-white';
hs.fadeInOut = true;
hs.dimmingOpacity = 0.75;

// Add the controlbar
hs.addSlideshow({
	//slideshowGroup: 'group1',
	interval: 5000,
	repeat: false,
	useControls: true,
	fixedControls: 'fit',
	overlayOptions: {
		opacity: .75,
		position: 'bottom center',
		hideOnMouseOut: true
	}
});

</script>

</head>

<body>

<div class="container">
  <div class="header">  <div class="menu">
  <?php $glav=1; $u=1; include("init/init_menu.php"); // подключение главного меню?>
  </div>
  <a href="<?php echo $SITE_URL; ?>"><img src="<?php echo SITE_URL; ?>images/logo_bar.jpg" width="960" height="120" border="0" alt="" /></a>
    <div class="banner">
    <object  width="960" height="70">
      <param name="src" value="<?php echo SITE_URL; ?>images/nevesta_long.swf" />
      <embed src="<?php echo SITE_URL; ?>images/nevesta_long.swf"  width="960" height="70"></embed>
    </object>
  </div>

<!-- end .header --></div>
  <div class="sidebar1">
    <p> Вышеуказанные ссылки демонстрируют базовую структуру навигации с использованием неупорядоченного списка, стилизованного при помощи CSS. Взяв ее за отправную точку и изменяя свойства, можно создать свой неповторимый дизайн. Если нужны выпадающие меню, их можно создать при помощи a Spry menu — мини-приложения menu из Adobe Exchange или ряда других инструментов javascript или CSS.</p>
    <p>Если нужна навигация вдоль верха, просто перенесите ul.nav в верх страницы и заново создайте стиль.</p>
    <!-- end .sidebar1 --></div>
  <div class="content">
    <h1>Инструкции</h1>
    <p>Помните, что в CSS этих макетов много комментариев. Если большинство операций выполняется в представлении Дизайн то имеет смысл посмотреть на код. Там есть советы по использованию CSS при работе с фиксированными макетами. Перед запуском сайта комментарии можно удалить. Чтобы узнать больше о техниках  используемых в этих макетах CSS прочтите следующую статью в Центре разработки Adobe: <a href="http://www.adobe.com/go/adc_css_layouts">http://www.adobe.com/go/adc_css_layouts</a>
</p>
    <h2>Метод очистки</h2>
    <p>Поскольку все столбцы обтекаемые, в этом макете в правиле .footer используется объявление clear:both. Эта техника очистки заставляет .container видеть места окончания столбцов, чтобы показать все границы или фоновые цвета, помещенные в .container. Если требуется удалить .footer из .container, то понадобится другой метод очистки. Надежнее всего будет добавить &lt;br class="clearfloat" /&gt; or &lt;div class="clearfloat"&gt;&lt;/div&gt; после последнего обтекаемого столбца (но до закрытия .container). Эффект очистки будет аналогичным.</p>
    <h3>Вставка логотипа</h3>
    <p>В этом макете установлен заполнитель рисунка — в .header, где, скорее всего, будет размещен логотип. Рекомендуется удалить заполнитель и заменить его своим логотипом со ссылкой. </p>
    <p> Помните, что при использовании инспектора свойств для перехода к своему логотипу при помощи поля SRC (вместо удаления и замены заполнителя) следует удалить встроенный фон и свойства экрана. Эти встроенные стили предназначены только для того, чтобы отображать заполнитель в браузере. </p>
    <p>Чтобы удалить встроенные стили, убедитесь, что для панели "Стили CSS" установлено значение "Текущий". Выберите изображение и в области "Свойства" на панели "Стили CSS" щелкните правой кнопкой мыши и удалите свойства экрана и фона. (Также всегда можно перейти прямо в код и удалить встроенные стили из изображения или заполнителя в коде.)</p>
    <!-- end .content --></div>
  <div class="sidebar2">
    <h4>Фоны</h4>
    <p>По своей сути фоновый цвет отображается в любом DIV только по длине содержимого. Если вы предпочитаете цвету разделительную линию, поместите границу сбоку DIV .content (но только если в нем всегда будет больше содержимого).</p>
    <!-- end .sidebar2 --></div>
  <div class="footer">
    <p>Этот .footer содержит объявление position:relative, чтобы Internet Explorer 6 получил hasLayout для .footer и правильно выполнил очистку. Если вам не нужна поддержка IE6, этот .footer можно удалить.</p>
    <!-- end .footer --></div>
  <!-- end .container --></div>
</body>
</html>

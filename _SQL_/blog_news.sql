-- phpMyAdmin SQL Dump
-- version 4.4.15.7
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1:3306
-- Время создания: Янв 20 2017 г., 14:03
-- Версия сервера: 5.6.31
-- Версия PHP: 5.6.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `blog_news`
--

-- --------------------------------------------------------

--
-- Структура таблицы `config`
--

CREATE TABLE IF NOT EXISTS `config` (
  `id` int(10) unsigned NOT NULL,
  `param_text` varchar(60) NOT NULL,
  `param_name` varchar(30) NOT NULL,
  `param_value` varchar(150) NOT NULL,
  `param_int` tinyint(1) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=39 DEFAULT CHARSET=utf8 COMMENT='изменяемые параметры сайта';

--
-- Дамп данных таблицы `config`
--

INSERT INTO `config` (`id`, `param_text`, `param_name`, `param_value`, `param_int`) VALUES
(2, 'Количество новостей на странице', 'maxNews', '5', 1),
(3, 'Контактный e-mail предприятия', 'email', 'test@test.ua', 0),
(5, 'Адрес предприятия', 'address', 'г. Запорожье, пр. Соборный дом - сами знаете, который', 0),
(7, 'Сайт предприятия', 'siteAddr', 'http://test.loc/', 0),
(20, 'E-mail администратора сайта или менеджера.', 'mailTo', 'MaxSol.ZP@gmail.com', 0),
(21, 'контактный телефон предприятия 1', 'phone1', '(061)222-32-23', 0),
(22, 'контактный телефон предприятия 2', 'phone2', '(063)322-32-23', 0),
(30, 'Ссылка в facebook', 'facebook', '#', 0),
(31, 'Ссылка в google+', 'google', '#', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `msf_admins`
--

CREATE TABLE IF NOT EXISTS `msf_admins` (
  `id` int(10) unsigned NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `skype` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `surname` varchar(100) NOT NULL,
  `blocked` tinyint(1) NOT NULL,
  `activate` tinyint(1) NOT NULL,
  `date_register` int(11) unsigned NOT NULL,
  `del` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `msf_admins`
--

INSERT INTO `msf_admins` (`id`, `email`, `password`, `skype`, `phone`, `name`, `surname`, `blocked`, `activate`, `date_register`, `del`) VALUES
(1, 'test@test.ua', '1f82ea75c5cc526729e2d581aeb3aeccfef4407e', '852456', '12345678', 'TestUser', 'Fam1', 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `msf_block_inst`
--

CREATE TABLE IF NOT EXISTS `msf_block_inst` (
  `id` int(10) unsigned NOT NULL,
  `name_block` varchar(40) NOT NULL,
  `tpl_block` varchar(40) NOT NULL,
  `range_block` int(3) unsigned NOT NULL,
  `activ_block` tinyint(1) NOT NULL,
  `required_block` tinyint(1) NOT NULL,
  `admin_page` tinyint(1) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `msf_block_inst`
--

INSERT INTO `msf_block_inst` (`id`, `name_block`, `tpl_block`, `range_block`, `activ_block`, `required_block`, `admin_page`) VALUES
(1, 'Header страницы', 'header.tpl.php', 1, 1, 1, 0),
(2, 'Головное иеню', 'headnav.tpl.php', 2, 1, 1, 0),
(3, 'большой слайдер', 'slider.tpl.php', 4, 0, 0, 0),
(4, 'заголовок страниц', 'headinfo.tpl.php', 6, 0, 0, 0),
(5, 'header информационного блока', 'main.header.tpl.php', 8, 1, 1, 0),
(6, 'Информационный блок - 1', 'main.block1.tpl.php', 10, 1, 0, 0),
(8, 'Информационный сайдбар - блок 1', 'sidebar.block1.tpl.php', 14, 0, 0, 0),
(10, 'footer информационного блока', 'main.footer.tpl.php', 18, 1, 1, 0),
(11, 'Footer страницы', 'footer.tpl.php', 20, 1, 1, 0),
(12, 'Админ-Header страницы', 'adm.header.tpl.php', 2, 1, 1, 1),
(13, 'Админ-блок Меню', 'adm.headnav.tpl.php', 4, 1, 1, 1),
(16, 'Админ-Информационный блок - Центр', 'admin.centr.tpl.php', 10, 1, 1, 1),
(19, 'Админ-footer страницы', 'adm.footer.tpl.php', 16, 1, 1, 1),
(20, 'Админ-Footer ind JS страницы', 'adm.footer.js.tpl.php', 18, 1, 1, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `msf_block_param`
--

CREATE TABLE IF NOT EXISTS `msf_block_param` (
  `id` int(10) unsigned NOT NULL,
  `id_block` varchar(100) NOT NULL,
  `lang` char(2) NOT NULL COMMENT 'язык параметра',
  `param_name` varchar(20) NOT NULL,
  `param_value` varchar(1000) NOT NULL,
  `comment` varchar(150) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=77 DEFAULT CHARSET=utf8 COMMENT='Параметры информационных блоков';

--
-- Дамп данных таблицы `msf_block_param`
--

INSERT INTO `msf_block_param` (`id`, `id_block`, `lang`, `param_name`, `param_value`, `comment`) VALUES
(52, 'f.soc', 'ru', 'sc-n-1', 'Facebook', 'Facebook'),
(51, 'f.soc', 'ru', 'sc-1', '#', 'Facebook'),
(50, 'f.soc', 'ru', 'soc-1', 'Мы в соцсетях', 'Мы в соцсетях'),
(1, 'config', 'ru', 'company', 'Киран', 'Название компании'),
(53, 'f.soc', 'ru', 'sc-2', '#', 'Twitter'),
(54, 'f.soc', 'ru', 'sc-n-2', 'Twitter', 'Twitter'),
(55, 'f.soc', 'ru', 'sc-3', '#', 'Google-plus'),
(56, 'f.soc', 'ru', 'sc-n-3', 'Google-plus', 'Google-plus'),
(57, 'f.soc', 'ru', 'sc-4', '#', 'Youtube'),
(58, 'f.soc', 'ru', 'sc-n-4', 'Youtube', 'Youtube'),
(59, 'f.soc', 'ru', 'sc-5', '#', 'Instagram'),
(60, 'f.soc', 'ru', 'sc-n-5', 'Instagram', 'Instagram'),
(61, 'f.soc', 'ru', 'sc-6', '#', 'Rss'),
(62, 'f.soc', 'ru', 'sc-n-6', 'Rss', 'Rss'),
(63, 'f.soc', 'ru', 'sc-7', '#', 'Pinterest'),
(64, 'f.soc', 'ru', 'sc-n-7', 'Pinterest', 'Pinterest'),
(65, 'f.soc', 'ru', 'sc-8', '', ''),
(66, 'f.soc', 'ru', 'sc-n-8', '', ''),
(67, 'f.soc', 'ru', 'sc-9', '', ''),
(68, 'f.soc', 'ru', 'sc-n-9', '', ''),
(69, 'f.soc', 'ru', 'sc-10', '', ''),
(70, 'f.soc', 'ru', 'sc-n-10', '', ''),
(71, 'catalog', 'ru', 'cena', 'Цена :', 'Цена'),
(72, 'catalog', 'ru', 'valuta', 'грн.', 'грн.'),
(73, 'config', 'ru', 'goodBase', 'Сохранено.', 'Запрос выполнен'),
(74, 'config', 'ru', 'badBase', 'Ошибка!', 'Запрос Не выполнен'),
(75, 'config', 'ru', 'badFile', 'Ошибка при загрузке', 'Ошибка при загрузке файла'),
(76, 'config', 'ru', 'goodFile', 'Файл успешно загружен', 'Файл загружен успешно');

-- --------------------------------------------------------

--
-- Структура таблицы `msf_main`
--

CREATE TABLE IF NOT EXISTS `msf_main` (
  `id` int(10) unsigned NOT NULL,
  `page` varchar(50) NOT NULL,
  `mainblock` mediumtext NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='Таблица для информации о основном блоке';

--
-- Дамп данных таблицы `msf_main`
--

INSERT INTO `msf_main` (`id`, `page`, `mainblock`) VALUES
(1, 'contact', '<table cellpadding="0" cellspacing="15" style="width:500px">\n	<tbody>\n		<tr>\n			<td style="text-align:left">\n			<h2><strong>Телефоны: </strong></h2>\n			</td>\n			<td><span dir="ltr"><span dir="ltr"><img src="resource://skype_ff_extension-at-jetpack/skype_ff_extension/data/call_skype_logo.png" style="height:0px; width:0px" />(061)-223-32-21</span></span><br />\n			<span dir="ltr"><span dir="ltr"><img src="resource://skype_ff_extension-at-jetpack/skype_ff_extension/data/call_skype_logo.png" style="height:0px; width:0px" />(063)-223-32-32</span></span></td>\n		</tr>\n		<tr>\n			<td>\n			<h2><strong>Email: </strong></h2>\n			</td>\n			<td>\n			<p>test@test.ua</p>\n			</td>\n		</tr>\n		<tr>\n			<td>\n			<h2><strong>Адрес: </strong></h2>\n			</td>\n			<td>г. Запорожье, пр. Соборный, дом - сами знаете, который</td>\n		</tr>\n	</tbody>\n</table>');

-- --------------------------------------------------------

--
-- Структура таблицы `msf_news`
--

CREATE TABLE IF NOT EXISTS `msf_news` (
  `id` int(10) unsigned NOT NULL,
  `id_firm` int(10) unsigned NOT NULL,
  `data` int(11) NOT NULL,
  `lang` char(2) NOT NULL,
  `title` varchar(500) NOT NULL,
  `img_news` varchar(50) NOT NULL,
  `text` varchar(10000) NOT NULL,
  `vis` tinyint(1) NOT NULL DEFAULT '1',
  `counter` int(10) unsigned NOT NULL,
  `img_count` int(5) unsigned NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COMMENT='новости компании';

--
-- Дамп данных таблицы `msf_news`
--

INSERT INTO `msf_news` (`id`, `id_firm`, `data`, `lang`, `title`, `img_news`, `text`, `vis`, `counter`, `img_count`) VALUES
(1, 0, 1393099200, 'ru', 'С Праздником - Днем защитника Отечества!', '', '<p>Коллектив специализированной мастерской &quot;ГБО Запорожье&quot; поздравляет своих клиентов с Днем защитника Отечества, праздником настоящих мужчин. Мирного неба, бодрости духа, веры в себя и удачи во всем.</p>', 0, 0, 0),
(2, 0, 1393510379, 'ru', 'Ответы на вопросы по установке и эксплуатации газобаллонного оборудования.', '', 'Предлагаем Вашему вниманию интересную подборку ответов на вопросы присланные посетителями нашего сайта. Работы по установке ГБО, различные типы оборудования и материалов для газовых установок, цены, сроки работ, особенности эксплуатации автомобилей на газу - об этом и многом другом Вы можете узнать в разделе сайта "посмотреть ответы".\n', 1, 0, 0),
(3, 0, 1394226000, 'ru', 'C Праздником 8-го Марта!', '', '<p>Дорогие наши женщины! Коллектив газовой мастерской &quot;АвтоГАЗ Запорожье&quot; искренне поздравляет Вас с Международным женским Днем, праздником весны, 8-е Марта! Желаем Вам счастья, здоровья, любви, улыбок и хорошего настрояния круглый год!</p>', 1, 0, 0),
(6, 0, 1397422800, 'ru', 'Доставка газобаллонного оборудования по всей Украине.', '', '<p>Сообщаем о появившейся возможности доставки газобаллонного оборудования, комплектующих и аксессуаров во все регионы Украины. Для осуществления доставки, Вам, следует подобрать необходимый товар с прайс-листа, позвонить нам и указать каким способом, и через какую службу доставки удобней всего получить заказ. Мы работаем практически со всеми известными курьерскими службами Украины: &laquo;Новая почта&raquo;, &laquo;Укрпочта&raquo;, &laquo;Интайм&raquo;, &laquo;Автолюкс&raquo;, &laquo;Гюнсел&raquo;, &laquo;Ночной экспресс&raquo;, &laquo;ДПСЗ&raquo;, &laquo;Почтово-грузовой курьер&raquo;, &laquo;Курьер&raquo;, &laquo;Курьерская служба доставки&raquo;, &laquo;Exmoto&raquo;, &laquo;Укркурьер&raquo; и другие. Для вашего удобства доставка возможна разными вариантами на склад курьерской службы или по указанному адресу. Также, приобретенный товар можно забрать путем самовывоза с нашего магазина расположенного по адресу: г. Запорожье, ул. Грязнова, 1-г. Звоните, заказывайте и экономьте уже сегодня.</p>', 1, 4, 0),
(10, 0, 1440450000, 'ru', 'Германию подозревают в нечестном получении права проведения Чемпионата мира', 'p10-1454926473-n.jpg', '<p><strong><em>В Германии разгорается коррупционный скандал по поводу получения права на проведение ЧМ-2016.</em></strong></p>\n\n<p>Der Spiegel сообщает, что четыре функционера FIFA получили взятки за свои голоса в пользу Германии.</p>\n\n<p>В 2000 году Роберт Луис-Дрейфус, занимавший в то время пост главы Adidas, передал организационному комитету Германии сумму в размере $7,6 млн. Эти деньги были выделены на взятки четырем членам исполнительного комитета FIFA , представляющим Азию.</p>\n\n<p>Также издание сообщает, что Франц Беккенбауэр и Вольфганг Нирсбах (нынешний президент Федерации футбола Германии) знали о подкупе функционеров.</p>\n\n<p>Ранее появилась информация, что правоохранительные органы Швейцарии открыли дело в отношении президента ФИФА Зеппа Блаттера.</p>\n\n<p>Напомним, в конце мая ряд чиновников организации были арестованы по обвинению во взяточничестве. Доказательства были получены властями США.</p>\n\n<p>После объявления Зеппа Блаттера об уходе с поста президента FIFA расследование продолжилось.На данный момент оно также касается выборов России и Катара в качестве стран-хозяек чемпионатов мира 2018 и 2022 годов соответственно.</p>\n\n<p>Ранее СПОРТ bigmir)net сообщал, что Интерпол прекратил сотрудничество с FIFA из-за коррупционного скандала в организации.</p>\n\n<p>Эта же новость на Спорт bigmir)net: Германию подозревают в нечестном получении права проведения Чемпионата мира 2006 года.</p>', 1, 3, 0),
(11, 0, 1454965200, '', '', '', '', 0, 0, 0),
(12, 0, 1454965200, '', '', '', '', 0, 0, 0),
(13, 0, 2, '', '', '', '', 0, 0, 0),
(14, 0, 1, '', '', '', '', 0, 0, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `msf_pages`
--

CREATE TABLE IF NOT EXISTS `msf_pages` (
  `id` int(10) unsigned NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `class` varchar(100) NOT NULL,
  `page_name` varchar(150) NOT NULL,
  `comment` varchar(150) NOT NULL,
  `title` varchar(150) NOT NULL,
  `descript` varchar(1000) NOT NULL,
  `keyword` varchar(1000) NOT NULL,
  `crumb` varchar(40) NOT NULL,
  `user_page` tinyint(1) NOT NULL DEFAULT '0',
  `admin_page` tinyint(1) NOT NULL DEFAULT '0',
  `lang` char(2) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COMMENT='Данные о страницах';

--
-- Дамп данных таблицы `msf_pages`
--

INSERT INTO `msf_pages` (`id`, `name`, `class`, `page_name`, `comment`, `title`, `descript`, `keyword`, `crumb`, `user_page`, `admin_page`, `lang`) VALUES
(1, 'page404', '', 'page404', '', 'Test Site Page 404', '', '', '', 0, 0, ''),
(2, 'index', '', 'Главная', '', 'Test Site Главная', '', '', '', 0, 0, ''),
(7, 'contact', '', 'Контакты', '', 'Test Site Контакты', '', '', '', 0, 0, ''),
(9, 'usvers', '', 'Админпанель', '', '', '', '', '', 0, 0, ''),
(14, 'adminnews', '', 'Админпанель - Новости', '', '', '', '', '', 0, 1, ''),
(15, 'adminpages', '', 'Админпанель - Редактируемые страницы', '', '', '', '', '', 0, 1, '');

-- --------------------------------------------------------

--
-- Структура таблицы `msf_page_block`
--

CREATE TABLE IF NOT EXISTS `msf_page_block` (
  `id` int(10) unsigned NOT NULL,
  `id_page` int(10) unsigned NOT NULL,
  `name_block` varchar(40) NOT NULL,
  `tpl_block` varchar(50) NOT NULL,
  `range_block` int(3) NOT NULL,
  `activ_block` tinyint(1) NOT NULL,
  `required_block` tinyint(1) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=179 DEFAULT CHARSET=utf8 COMMENT='перечень блоков на странице';

--
-- Дамп данных таблицы `msf_page_block`
--

INSERT INTO `msf_page_block` (`id`, `id_page`, `name_block`, `tpl_block`, `range_block`, `activ_block`, `required_block`) VALUES
(1, 1, 'Header страницы', 'header.tpl.php', 1, 1, 1),
(2, 1, 'Головное иеню', 'headnav.tpl.php', 2, 1, 1),
(3, 1, 'большой слайдер', 'slider.tpl.php', 4, 0, 0),
(4, 1, 'заголовок страниц', 'headinfo.tpl.php', 6, 0, 0),
(5, 1, 'header информационного блока', 'main.header.tpl.php', 8, 1, 1),
(6, 1, 'Информационный блок - 1', 'page404.tpl.php', 10, 1, 0),
(8, 1, 'Информационный сайдбар - блок 1', 'sidebar.block1.tpl.php', 14, 0, 0),
(10, 1, 'footer информационного блока', 'main.footer.tpl.php', 18, 1, 1),
(11, 1, 'Footer страницы', 'footer.tpl.php', 20, 1, 1),
(12, 2, 'Header страницы', 'header.tpl.php', 1, 1, 1),
(13, 2, 'Головное иеню', 'headnav.tpl.php', 2, 1, 1),
(14, 2, 'большой слайдер', 'slider.tpl.php', 4, 0, 0),
(15, 2, 'header информационного блока', 'main.header.tpl.php', 6, 1, 0),
(16, 2, 'Новости блога', 'news.tpl.php', 8, 1, 1),
(19, 2, 'Информационный сайдбар - блок 1', 'sidebar.block1.tpl.php', 14, 0, 0),
(21, 2, 'footer информационного блока', 'main.footer.tpl.php', 18, 1, 1),
(22, 2, 'Footer страницы', 'footer.tpl.php', 20, 1, 1),
(67, 7, 'Header страницы', 'header.tpl.php', 1, 1, 1),
(68, 7, 'Головное иеню', 'headnav.tpl.php', 2, 1, 1),
(69, 7, 'большой слайдер', 'slider.tpl.php', 4, 0, 0),
(70, 7, 'заголовок страниц', 'headinfo.tpl.php', 6, 1, 0),
(71, 7, 'header информационного блока', 'main.header.tpl.php', 8, 1, 1),
(72, 7, 'Информационный редактируемый блок', 'main.block.tpl.php', 10, 1, 0),
(73, 7, 'Информационный блок - 2', 'main.block2.tpl.php', 12, 0, 0),
(74, 7, 'Информационный сайдбар - блок 1', 'maps.tpl.php', 14, 0, 0),
(75, 7, 'Информационный сайдбар - блок 2', 'sidebar.block2.tpl.php', 16, 0, 0),
(76, 7, 'footer информационного блока', 'main.footer.tpl.php', 18, 1, 1),
(77, 7, 'Footer страницы', 'footer.tpl.php', 20, 1, 1),
(89, 9, 'Админ-Header страницы', 'adm.header.tpl.php', 2, 1, 1),
(90, 9, 'Админ-блок Меню', 'adm.headnav.tpl.php', 4, 1, 1),
(91, 9, 'Админ-Информационный блок - Верх', 'admin.top.tpl.php', 6, 0, 0),
(92, 9, 'Админ-Информационный блок - Левая часть', 'admin.left.tpl.php', 8, 0, 0),
(93, 9, 'Админ-Информационный блок - Центр', 'adm.usver.tpl.php', 10, 1, 1),
(94, 9, 'Админ-Информационный блок - Правая часть', 'admin.right.tpl.php', 12, 0, 0),
(95, 9, 'Админ-Информационный блок - Низ', 'admin.bottom.tpl.php', 14, 0, 0),
(96, 9, 'Админ-footer страницы', 'adm.footer.tpl.php', 16, 1, 1),
(97, 9, 'Админ-Footer ind JS страницы', 'adm.footer.js.tpl.php', 18, 1, 1),
(98, 10, 'Админ-Header страницы', 'adm.header.tpl.php', 2, 1, 1),
(99, 10, 'Админ-блок Меню', 'adm.headnav.tpl.php', 4, 1, 1),
(100, 10, 'Админ-Информационный блок - Верх', 'admin.top.tpl.php', 6, 0, 0),
(101, 10, 'Админ-Информационный блок - Левая часть', 'admin.left.tpl.php', 8, 0, 0),
(102, 10, 'Админ-Информационный блок - Центр', 'adm.category.tpl.php', 10, 1, 1),
(103, 10, 'Админ-Информационный блок - Правая часть', 'admin.right.tpl.php', 12, 0, 0),
(104, 10, 'Админ-Информационный блок - Низ', 'admin.bottom.tpl.php', 14, 0, 0),
(105, 10, 'Админ-footer страницы', 'adm.footer.tpl.php', 16, 1, 1),
(106, 10, 'Админ-Footer ind JS страницы', 'adm.footer.js.tpl.php', 18, 1, 1),
(107, 11, 'Админ-Header страницы', 'adm.header.tpl.php', 2, 1, 1),
(108, 11, 'Админ-блок Меню', 'adm.headnav.tpl.php', 4, 1, 1),
(109, 11, 'Админ-Информационный блок - Верх', 'admin.top.tpl.php', 6, 0, 0),
(110, 11, 'Админ-Информационный блок - Левая часть', 'admin.left.tpl.php', 8, 0, 0),
(111, 11, 'Админ-Информационный блок - Центр', 'adm.catalog.tpl.php', 10, 1, 1),
(112, 11, 'Админ-Информационный блок - Правая часть', 'admin.right.tpl.php', 12, 0, 0),
(113, 11, 'Админ-Информационный блок - Низ', 'admin.bottom.tpl.php', 14, 0, 0),
(114, 11, 'Админ-footer страницы', 'adm.footer.tpl.php', 16, 1, 1),
(115, 11, 'Админ-Footer ind JS страницы', 'adm.footer.js.tpl.php', 18, 1, 1),
(116, 12, 'Админ-Header страницы', 'adm.header.tpl.php', 2, 1, 1),
(117, 12, 'Админ-блок Меню', 'adm.headnav.tpl.php', 4, 1, 1),
(118, 12, 'Админ-Информационный блок - Верх', 'admin.top.tpl.php', 6, 0, 0),
(119, 12, 'Админ-Информационный блок - Левая часть', 'admin.left.tpl.php', 8, 0, 0),
(120, 12, 'Админ-Информационный блок - Центр', 'adm.catalog.tpl.php', 10, 1, 1),
(121, 12, 'Админ-Информационный блок - Правая часть', 'admin.right.tpl.php', 12, 0, 0),
(122, 12, 'Админ-Информационный блок - Низ', 'admin.bottom.tpl.php', 14, 0, 0),
(123, 12, 'Админ-footer страницы', 'adm.footer.tpl.php', 16, 1, 1),
(124, 12, 'Админ-Footer ind JS страницы', 'adm.footer.js.tpl.php', 18, 1, 1),
(125, 13, 'Админ-Header страницы', 'adm.header.tpl.php', 2, 1, 1),
(126, 13, 'Админ-блок Меню', 'adm.headnav.tpl.php', 4, 1, 1),
(127, 13, 'Админ-Информационный блок - Верх', 'admin.top.tpl.php', 6, 0, 0),
(128, 13, 'Админ-Информационный блок - Левая часть', 'admin.left.tpl.php', 8, 0, 0),
(129, 13, 'Админ-Информационный блок - Центр', 'adm.faq.tpl.php', 10, 1, 1),
(130, 13, 'Админ-Информационный блок - Правая часть', 'admin.right.tpl.php', 12, 0, 0),
(131, 13, 'Админ-Информационный блок - Низ', 'admin.bottom.tpl.php', 14, 0, 0),
(132, 13, 'Админ-footer страницы', 'adm.footer.tpl.php', 16, 1, 1),
(133, 13, 'Админ-Footer ind JS страницы', 'adm.footer.js.tpl.php', 18, 1, 1),
(170, 18, 'Админ-Header страницы', 'adm.header.tpl.php', 2, 1, 1),
(134, 14, 'Админ-Header страницы', 'adm.header.tpl.php', 2, 1, 1),
(135, 14, 'Админ-блок Меню', 'adm.headnav.tpl.php', 4, 1, 1),
(136, 14, 'Админ-Информационный блок - Верх', 'admin.top.tpl.php', 6, 0, 0),
(137, 14, 'Админ-Информационный блок - Левая часть', 'admin.left.tpl.php', 8, 0, 0),
(138, 14, 'Админ-Информационный блок - Центр', 'adm.news.tpl.php', 10, 1, 1),
(139, 14, 'Админ-Информационный блок - Правая часть', 'admin.right.tpl.php', 12, 0, 0),
(140, 14, 'Админ-Информационный блок - Низ', 'admin.bottom.tpl.php', 14, 0, 0),
(141, 14, 'Админ-footer страницы', 'adm.footer.tpl.php', 16, 1, 1),
(142, 14, 'Админ-Footer ind JS страницы', 'adm.footer.js.tpl.php', 18, 1, 1),
(143, 15, 'Админ-Header страницы', 'adm.header.tpl.php', 2, 1, 1),
(144, 15, 'Админ-блок Меню', 'adm.headnav.tpl.php', 4, 1, 1),
(145, 15, 'Админ-Информационный блок - Верх', 'admin.top.tpl.php', 6, 0, 0),
(146, 15, 'Админ-Информационный блок - Левая часть', 'admin.left.tpl.php', 8, 0, 0),
(147, 15, 'Админ-Информационный блок - Центр', 'adm.pages.tpl.php', 10, 1, 1),
(148, 15, 'Админ-Информационный блок - Правая часть', 'admin.right.tpl.php', 12, 0, 0),
(149, 15, 'Админ-Информационный блок - Низ', 'admin.bottom.tpl.php', 14, 0, 0),
(150, 15, 'Админ-footer страницы', 'adm.footer.tpl.php', 16, 1, 1),
(151, 15, 'Админ-Footer ind JS страницы', 'adm.footer.js.tpl.php', 18, 1, 1),
(152, 16, 'Админ-Header страницы', 'adm.header.tpl.php', 2, 1, 1),
(153, 16, 'Админ-блок Меню', 'adm.headnav.tpl.php', 4, 1, 1),
(154, 16, 'Админ-Информационный блок - Верх', 'admin.top.tpl.php', 6, 0, 0),
(155, 16, 'Админ-Информационный блок - Левая часть', 'admin.left.tpl.php', 8, 0, 0),
(156, 16, 'Админ-Информационный блок - Центр', 'adm.config.tpl.php', 10, 1, 1),
(157, 16, 'Админ-Информационный блок - Правая часть', 'admin.right.tpl.php', 12, 0, 0),
(158, 16, 'Админ-Информационный блок - Низ', 'admin.bottom.tpl.php', 14, 0, 0),
(159, 16, 'Админ-footer страницы', 'adm.footer.tpl.php', 16, 1, 1),
(160, 16, 'Админ-Footer ind JS страницы', 'adm.footer.js.tpl.php', 18, 1, 1),
(161, 17, 'Админ-Header страницы', 'adm.header.tpl.php', 2, 1, 1),
(162, 17, 'Админ-блок Меню', 'adm.headnav.tpl.php', 4, 1, 1),
(163, 17, 'Админ-Информационный блок - Верх', 'admin.top.tpl.php', 6, 0, 0),
(164, 17, 'Админ-Информационный блок - Левая часть', 'admin.left.tpl.php', 8, 0, 0),
(165, 17, 'Админ-Информационный блок - Центр', 'adm.admin.tpl.php', 10, 1, 1),
(166, 17, 'Админ-Информационный блок - Правая часть', 'admin.right.tpl.php', 12, 0, 0),
(167, 17, 'Админ-Информационный блок - Низ', 'admin.bottom.tpl.php', 14, 0, 0),
(168, 17, 'Админ-footer страницы', 'adm.footer.tpl.php', 16, 1, 1),
(169, 17, 'Админ-Footer ind JS страницы', 'adm.footer.js.tpl.php', 18, 1, 1),
(171, 18, 'Админ-блок Меню', 'adm.headnav.tpl.php', 4, 1, 1),
(172, 18, 'Админ-Информационный блок - Верх', 'admin.top.tpl.php', 6, 0, 0),
(173, 18, 'Админ-Информационный блок - Левая часть', 'admin.left.tpl.php', 8, 0, 0),
(174, 18, 'Админ-Информационный блок - Центр', 'adm.slider.tpl.php', 10, 1, 1),
(175, 18, 'Админ-Информационный блок - Правая часть', 'admin.right.tpl.php', 12, 0, 0),
(176, 18, 'Админ-Информационный блок - Низ', 'admin.bottom.tpl.php', 14, 0, 0),
(177, 18, 'Админ-footer страницы', 'adm.footer.tpl.php', 16, 1, 1),
(178, 18, 'Админ-Footer ind JS страницы', 'adm.footer.js.tpl.php', 18, 1, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `msf_slider`
--

CREATE TABLE IF NOT EXISTS `msf_slider` (
  `id` int(10) unsigned NOT NULL,
  `type` int(1) unsigned NOT NULL DEFAULT '1',
  `link` int(1) unsigned NOT NULL DEFAULT '1',
  `img` varchar(50) NOT NULL,
  `text` varchar(500) NOT NULL,
  `vis` tinyint(3) unsigned NOT NULL,
  `lang` varchar(2) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `msf_slider`
--

INSERT INTO `msf_slider` (`id`, `type`, `link`, `img`, `text`, `vis`, `lang`) VALUES
(7, 2, 1, 'p7-1456962749-n.jpg', '', 1, '');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `config`
--
ALTER TABLE `config`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `msf_admins`
--
ALTER TABLE `msf_admins`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `msf_block_inst`
--
ALTER TABLE `msf_block_inst`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `msf_block_param`
--
ALTER TABLE `msf_block_param`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_block` (`id_block`),
  ADD KEY `lang` (`lang`);

--
-- Индексы таблицы `msf_main`
--
ALTER TABLE `msf_main`
  ADD PRIMARY KEY (`id`),
  ADD KEY `page` (`page`);

--
-- Индексы таблицы `msf_news`
--
ALTER TABLE `msf_news`
  ADD PRIMARY KEY (`id`),
  ADD KEY `data` (`data`),
  ADD KEY `lang` (`lang`),
  ADD KEY `id_firm` (`id_firm`);

--
-- Индексы таблицы `msf_pages`
--
ALTER TABLE `msf_pages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`);

--
-- Индексы таблицы `msf_page_block`
--
ALTER TABLE `msf_page_block`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_page` (`id_page`);

--
-- Индексы таблицы `msf_slider`
--
ALTER TABLE `msf_slider`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `config`
--
ALTER TABLE `config`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=39;
--
-- AUTO_INCREMENT для таблицы `msf_admins`
--
ALTER TABLE `msf_admins`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `msf_block_inst`
--
ALTER TABLE `msf_block_inst`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT для таблицы `msf_block_param`
--
ALTER TABLE `msf_block_param`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=77;
--
-- AUTO_INCREMENT для таблицы `msf_main`
--
ALTER TABLE `msf_main`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `msf_news`
--
ALTER TABLE `msf_news`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT для таблицы `msf_pages`
--
ALTER TABLE `msf_pages`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT для таблицы `msf_page_block`
--
ALTER TABLE `msf_page_block`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=179;
--
-- AUTO_INCREMENT для таблицы `msf_slider`
--
ALTER TABLE `msf_slider`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

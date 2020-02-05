<?php
/**
 * Основные параметры WordPress.
 *
 * Скрипт для создания wp-config.php использует этот файл в процессе
 * установки. Необязательно использовать веб-интерфейс, можно
 * скопировать файл в "wp-config.php" и заполнить значения вручную.
 *
 * Этот файл содержит следующие параметры:
 *
 * * Настройки MySQL
 * * Секретные ключи
 * * Префикс таблиц базы данных
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** Параметры MySQL: Эту информацию можно получить у вашего хостинг-провайдера ** //
/** Имя базы данных для WordPress */
define('DB_NAME', 'wordpress');

/** Имя пользователя MySQL */
define('DB_USER', 'wordpress');

/** Пароль к базе данных MySQL */
define('DB_PASSWORD', 'wordpress');

/** Имя сервера MySQL */
define('DB_HOST', '192.168.1.20');

/** Кодировка базы данных для создания таблиц. */
define('DB_CHARSET', 'utf8mb4');

/** Схема сопоставления. Не меняйте, если не уверены. */
define('DB_COLLATE', '');

/**#@+
 * Уникальные ключи и соли для аутентификации.
 *
 * Смените значение каждой константы на уникальную фразу.
 * Можно сгенерировать их с помощью {@link https://api.wordpress.org/secret-key/1.1/salt/ сервиса ключей на WordPress.org}
 * Можно изменить их, чтобы сделать существующие файлы cookies недействительными. Пользователям потребуется авторизоваться снова.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'gNQ3LkBqP>x SLQcT>dy<yYyU,?:1<B1.dG;bL)iU[+^BhrO9a5ddsoo,3%9O#Lu');
define('SECURE_AUTH_KEY',  ';qUY]!Pg{RcC#5/dHsYADE$tyEr{L32Ap*WQFerR6<l&@p=-sNqr)v_;; txd;.r');
define('LOGGED_IN_KEY',    '+=jPTH/$SdF;rO#kE+.@S=~r6m4ei-N:FPQ1N?h]_l>qR&IxaHw)DV-2w0K2L=c1');
define('NONCE_KEY',        'yY%OCX)$p/.,k%9*$D}pK!  :W.NV}q?aD/Qhy4V[dfq?$QA#pl*Wj(g/O1MNbD2');
define('AUTH_SALT',        '[wBL&+E)EQ02SS_mX 3ojE5LX^yRV_4I:ANSMm>,3I?ut[/xvq=;QE.f3Z*tn/ew');
define('SECURE_AUTH_SALT', 'Eo+&zTw52o{5Od) xEOCer+0SRGN)B}RBBOswIFJYiH=,Gc~.Cm@.+Q/QGh9TY>t');
define('LOGGED_IN_SALT',   '[%:IiKW],(k>@HeDY<Of&ONm>}4N&fx/X0H`mpQ6|N:;}//}p!Gj>h{+`,&3&+cd');
define('NONCE_SALT',       'z2skSEM53$ >b16~wn3@?1kDrTB]s +5J07v~;S((fY(p$ji4HX7;TL:w 2wV:u&');

/**#@-*/

/**
 * Префикс таблиц в базе данных WordPress.
 *
 * Можно установить несколько сайтов в одну базу данных, если использовать
 * разные префиксы. Пожалуйста, указывайте только цифры, буквы и знак подчеркивания.
 */
$table_prefix  = 'wp_';

/**
 * Для разработчиков: Режим отладки WordPress.
 *
 * Измените это значение на true, чтобы включить отображение уведомлений при разработке.
 * Разработчикам плагинов и тем настоятельно рекомендуется использовать WP_DEBUG
 * в своём рабочем окружении.
 *
 * Информацию о других отладочных константах можно найти в Кодексе.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* Это всё, дальше не редактируем. Успехов! */

/** Абсолютный путь к директории WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Инициализирует переменные WordPress и подключает файлы. */
require_once(ABSPATH . 'wp-settings.php');


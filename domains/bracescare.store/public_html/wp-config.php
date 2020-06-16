<?php
/**
 * Cấu hình cơ bản cho WordPress
 *
 * Trong quá trình cài đặt, file "wp-config.php" sẽ được tạo dựa trên nội dung 
 * mẫu của file này. Bạn không bắt buộc phải sử dụng giao diện web để cài đặt, 
 * chỉ cần lưu file này lại với tên "wp-config.php" và điền các thông tin cần thiết.
 *
 * File này chứa các thiết lập sau:
 *
 * * Thiết lập MySQL
 * * Các khóa bí mật
 * * Tiền tố cho các bảng database
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** Thiết lập MySQL - Bạn có thể lấy các thông tin này từ host/server ** //
/** Tên database MySQL */
define( 'DB_NAME', 'bracestt27uq_bracescare' );

/** Username của database */
define( 'DB_USER', 'bracestt27uq_bracescare' );

/** Mật khẩu của database */
define( 'DB_PASSWORD', 'Maytamnuoc123' );

/** Hostname của database */
define( 'DB_HOST', 'localhost' );

/** Database charset sử dụng để tạo bảng database. */
define( 'DB_CHARSET', 'utf8mb4' );

/** Kiểu database collate. Đừng thay đổi nếu không hiểu rõ. */
define('DB_COLLATE', '');

/**#@+
 * Khóa xác thực và salt.
 *
 * Thay đổi các giá trị dưới đây thành các khóa không trùng nhau!
 * Bạn có thể tạo ra các khóa này bằng công cụ
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * Bạn có thể thay đổi chúng bất cứ lúc nào để vô hiệu hóa tất cả
 * các cookie hiện có. Điều này sẽ buộc tất cả người dùng phải đăng nhập lại.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'cw[Ra@EqEHH:J=5K%LAJoIX184c2sw=NEy}uv7q|f&;8C}GYt*&ozB5GcS,*Ll!8' );
define( 'SECURE_AUTH_KEY',  '%h;)1jUA)u37o {ziq;oJJ90FI:,:U!n1$VC7GAsRZpS}SMMDcx:7O:=Y41;:cSk' );
define( 'LOGGED_IN_KEY',    '2h.Q($Vp2Rg,JHiyk>^5^UfC+]5ub:r3,S p;Ue).s:-KV3dTw$ex<nHkL0$/kWo' );
define( 'NONCE_KEY',        ' w?Yz>ZK/)Tk<7uUK7UnPy7hqfvE=eL3<laRk&wK%s|Qud=SvI9eE?m:!bUuBgRy' );
define( 'AUTH_SALT',        'PLehhI>dC$/x,[IK*d`l+@j[/fqk^.y#|,[8Y4]9XqiuK?1iyakK )#=P@~yG-|.' );
define( 'SECURE_AUTH_SALT', '9(TW6HPyOIa}k~oW(H8&ZxL$$YhI[G: lYd5cAoQ<tO5JQ11mmy&mgR{s`]Sx{M7' );
define( 'LOGGED_IN_SALT',   's6B8!2t$0G)gsg`u^h49b{Y_CH<4nKyN`hM*&wX9*Pm8g<obsd&1v$?T|gdcv8h(' );
define( 'NONCE_SALT',       'nJ|ClwAJO0P!zn{z]Pg$5GNWq7tY84=DQH}:21@He`Bp%m9tuY%U@XS4)2p3U$J_' );

/**#@-*/

/**
 * Tiền tố cho bảng database.
 *
 * Đặt tiền tố cho bảng giúp bạn có thể cài nhiều site WordPress vào cùng một database.
 * Chỉ sử dụng số, ký tự và dấu gạch dưới!
 */
$table_prefix  = 'wp_';

/**
 * Dành cho developer: Chế độ debug.
 *
 * Thay đổi hằng số này thành true sẽ làm hiện lên các thông báo trong quá trình phát triển.
 * Chúng tôi khuyến cáo các developer sử dụng WP_DEBUG trong quá trình phát triển plugin và theme.
 *
 * Để có thông tin về các hằng số khác có thể sử dụng khi debug, hãy xem tại Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* Đó là tất cả thiết lập, ngưng sửa từ phần này trở xuống. Chúc bạn viết blog vui vẻ. */

/** Đường dẫn tuyệt đối đến thư mục cài đặt WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Thiết lập biến và include file. */
require_once(ABSPATH . 'wp-settings.php');
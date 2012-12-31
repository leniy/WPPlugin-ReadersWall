<?php
/*
	Plugin Name:Readers Wall
	Plugin URI: http://blog.leniy.info/readers-wall.html
	Description: 高度自定制性能的读者墙
	Version: 0.1
	Author: leniy
	Author URI: http://blog.leniy.info/
*/

/*
	Copyright 2012 Leniy (m@leniy.info)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as 
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

register_activation_hook(__FILE__, 'qw_RW_act');
register_deactivation_hook(__FILE__, 'qw_RW_deact');

function qw_RW_setdefault() {
	$default_css = "
.RW-btn {
    padding-left:50px;
    height:50px;
    display:inline-block;
    position:relative;
    border:1px solid #80ab5d;
    -webkit-box-shadow:0px 1px 1px rgba(255,255,255,0.8) inset, 1px 1px 3px rgba(0,0,0,0.2);
    -moz-box-shadow:0px 1px 1px rgba(255,255,255,0.8) inset, 1px 1px 3px rgba(0,0,0,0.2);
    box-shadow:0px 1px 1px rgba(255,255,255,0.8) inset, 1px 1px 3px rgba(0,0,0,0.2);
    -webkit-border-radius:4px;
    -moz-border-radius:4px;
    border-radius:4px;
    clear:both;
    overflow:hidden;
    -webkit-transition:box-shadow 0.3s ease-in-out;
    -moz-transition:box-shadow 0.3s ease-in-out;
    -o-transition:box-shadow 0.3s ease-in-out;
    transition:box-shadow 0.3s ease-in-out;
}
.RW-btn img{
    position:absolute;
    left:0px;
    top:0px;
    width:100%; 
    height:100%;
    max-width:100%;
    max-height:100%;
    border:none;
    -webkit-transition:all 0.3s ease-in-out;
    -moz-transition:all 0.3s ease-in-out;
    -o-transition:all 0.3s ease-in-out;
    transition:all 0.3s ease-in-out;
}
.RW-btn .RW-btn-slide-text{
    position:absolute;
    font-size:20px;
    top:10px;
    left:10px;
    color:#6d954e;
    opacity:0;
    text-shadow:0px 1px 1px rgba(255,255,255,0.4);
    -webkit-transition:opacity 0.2s ease-in-out;
    -moz-transition:opacity 0.2s ease-in-out;
    -o-transition:opacity 0.2s ease-in-out;
    transition:opacity 0.2s ease-in-out;
}



.RW-btn:hover{
    -webkit-box-shadow:0px 1px 1px rgba(255,255,255,0.8) inset, 1px 1px 5px rgba(0,0,0,0.4);
    -moz-box-shadow:0px 1px 1px rgba(255,255,255,0.8) inset, 1px 1px 5px rgba(0,0,0,0.4);
    box-shadow:0px 1px 1px rgba(255,255,255,0.8) inset, 1px 1px 5px rgba(0,0,0,0.4);
}
.RW-btn:hover img{
    -webkit-transform:scale(10);
    -moz-transform:scale(10);
    -ms-transform:scale(10);
    -o-transform:scale(10);
    transform:scale(10);
    opacity:0;
}
.RW-btn:hover .RW-btn-slide-text{
    opacity:1;
}
.RW-btn:active{
    position:relative;
    top:1px;
    background:#80ab5d;
    -webkit-box-shadow:1px 1px 2px rgba(0,0,0,0.4) inset;
    -moz-box-shadow:1px 1px 2px rgba(0,0,0,0.4) inset;
    box-shadow:1px 1px 2px rgba(0,0,0,0.4) inset;
    border-color:#a9db80;
}
";
	$default_shortcode = "readerswall";
	update_option("qw_RW_css", $default_css);//保存css样式
	update_option("qw_RW_shortcode", $default_shortcode);//保存文章调用时的短代码
	update_option("qw_RW_shownumber", "64");//展示评论数排名前多少的用户
	update_option("qw_RW_days", "180");//统计多少天内的评论
}

function qw_RW_act() {
	add_option("qw_RW_css", $default_css);//保存css样式
	add_option("qw_RW_shortcode", $default_shortcode);//保存文章调用时的短代码
	add_option("qw_RW_shownumber", "10");//展示评论数排名前多少的用户
	add_option("qw_RW_days", "180");//统计多少天内的评论
	qw_RW_setdefault();
}

function qw_RW_deact() {
	delete_option("qw_RW_css");
	delete_option("qw_RW_shortcode");
	delete_option("qw_RW_shownumber");
	delete_option("qw_RW_days");
}

if (is_admin()) {
	add_action('admin_menu', 'qw_RW_menu');
}

function qw_RW_menu() {
	add_options_page( "Reasers Wall读者墙", "Reasers Wall读者墙", "administrator", 'RW.php', 'qw_RW_setpage');
}

/**********************************************************************/
/*************************   下面是设置页面   *************************/
/**********************************************************************/
function qw_RW_setpage() {
	echo "	<h2>Reader's Wall插件设置</h2>";
	if($_POST['qw_RW_btn']=="更新") {
		update_option("qw_RW_css", $_POST['qw_RW_css']);
		update_option("qw_RW_shortcode", $_POST['qw_RW_shortcode']);
		update_option("qw_RW_shownumber", $_POST['qw_RW_shownumber']);
		update_option("qw_RW_days", $_POST['qw_RW_days']);
		echo "<div id=\"message\" class=\"rwupdate\"><p>更新保存成功，请在文章中插入[" . get_option("qw_RW_shortcode") . "]启用读者墙</p></div>";
	}
	if($_POST['qw_RW_resetbtn']=="恢复默认设置") {
		qw_RW_setdefault();
		echo "<div id=\"message\" class=\"rwupdate\"><p>恢复成功，请在文章中插入[" . get_option("qw_RW_shortcode") . "]启用读者墙</p></div>";
	}

	?>
	<style type="text/css">
	.rwupdate {
		background-color: #D6F8AB;
		border-color: #E6DB55;
		margin: 5px 0 15px;
		padding: 0 .6em;
		border-width: 1px;
		border-style: solid;
		font-size: 12px;
		line-height: 1.4em;
		border-radius: 5px;
		width: 96%;
	}
	</style>

	<div><form method="post">
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row">调用时的短代码</th>
					<td>
						<input class="regular-text" name="qw_RW_shortcode" type="text" id="qw_RW_shortcode" value="<?php echo get_option("qw_RW_shortcode"); ?>" />
						<p>即[]中间的内容。例：使用[readerswall]调用，则填写readerswall，不包含两边的方括号</p>
					</td>
				</tr>
				<tr>
					<th scope="row">展示评论数排名前多少的用户</th>
					<td>
						<input class="regular-text" name="qw_RW_shownumber" type="text" id="qw_RW_shownumber" value="<?php echo get_option("qw_RW_shownumber"); ?>" />
						<p>评论数排名前几的才会显示？</p>
					</td>
				</tr>
				<tr>
					<th scope="row">依照多少天内的评论进行排名</th>
					<td>
						<input class="regular-text" name="qw_RW_days" type="text" id="qw_RW_days" value="<?php echo get_option("qw_RW_days"); ?>" />天
						<p>如果需要展示全部日期评论的排名，只需填入足够大的数字即可，例如99999</p>
					</td>
				</tr>
				<tr>
					<th scope="row">css样式</th>
					<td>
						<textarea name="qw_RW_css" rows="5" cols="50" id="qw_RW_css" class="large-text code"><?php echo get_option('qw_RW_css'); ?></textarea>
						<p>请确保输入的css正确无误</p>
					</td>
				</tr>
			</tbody>
		</table>
		<input type="submit" name="qw_RW_btn" value="更新" class="button-primary" />
		<input type="submit" name="qw_RW_resetbtn" value="恢复默认设置" class="button-primary" />
	</form></div>
	<hr>
	<iframe frameborder="0" src="http://blog.leniy.info/readers-wall.html" scrolling="auto" noresize="" width="100%" height="500px"></iframe>
	<?php
}

/**********************************************************************/
/**********************   下面是读者墙显示函数   **********************/
/**********************************************************************/
add_shortcode(get_option("qw_RW_shortcode"), "qw_RW_page");

function qw_RW_page() {
	global $wpdb;
	$qw_RW_shownumber = get_option('qw_RW_shownumber');
	$qw_RW_days = get_option('qw_RW_days');
	$query = "
	SELECT
		COUNT( comment_author_email ) AS number,
		comment_author_email,
		comment_author,
		comment_author_url
	FROM (
		SELECT *
		FROM $wpdb->comments
		LEFT OUTER JOIN $wpdb->posts
		ON ( $wpdb->posts.ID = $wpdb->comments.comment_post_ID )
		WHERE
				comment_date > date_sub( NOW(), INTERVAL $qw_RW_days DAY )
			AND user_id = '0'
			AND comment_approved =  '1'
	) AS tempcmt
	GROUP BY comment_author_email
	ORDER BY number DESC
	LIMIT {$qw_RW_shownumber}
	";
	$RWs = $wpdb->get_results($query);
	foreach ($RWs as $RW) {
		$temp_number = $RW->number;
		$temp_email = $RW->comment_author_email;
		$temp_author = $RW->comment_author;
		$temp_url = $RW->comment_author_url ? $RW->comment_author_url : "#";
		$temp_avatar = get_avatar($RW, 50, plugins_url('readers-wall/default.png'));

		$tmp = "
<a title=\"" . $temp_author . "  (" . $temp_number . "条评论)\" href=\"" . $temp_url . "\" target=\"_blank\" class=\"RW-btn\" rel=\"nofollow\">
" . "<span class=\"RW-btn-slide-text\">" . $temp_number . "</span>" . $temp_avatar . "</a>";
		$output .= $tmp;
	}
	$output = "<div class=\"readers-wall\">" . $output . "</div>";
	$css = get_option('qw_RW_css');
	return "<style type=\"text/css\">" . $css . "</style>" . $output;
}

/*
	References:
		1.Part of the css3 style from "Animated Buttons with CSS3"(Author:Anonymous)
		2.Mysql code for count of the comments came from zhidao.baidu.com
*/


?>

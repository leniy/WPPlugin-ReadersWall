<?php
/*
	Plugin Name:Readers Wall
	Plugin URI: http://blog.leniy.org/readers-wall.html
	Description: 高度自定制性能的读者墙
	Version: 1.1.9
	Author: leniy
	Author URI: http://blog.leniy.org/
*/

/*
	Copyright 2012 Leniy (m@leniy.org)

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

$thisplugin_author = "Leniy";
$thisplugin_url = "http://blog.leniy.org/readers-wall.html";
$thisplugin_name = "Readers Wall";
$thisplugin_version = "1.1.8";

register_activation_hook(__FILE__, 'qw_RW_act');
register_deactivation_hook(__FILE__, 'qw_RW_deact');

function qw_RW_setdefault() {
	$default_css = "animated_with_css3.css";
	$default_shortcode = "readerswall";
	update_option("qw_RW_css", $default_css);//保存css样式文件名
	update_option("qw_RW_shortcode", $default_shortcode);//保存文章调用时的短代码
	update_option("qw_RW_shownumber", "64");//展示评论数排名前多少的用户
	update_option("qw_RW_commentatleast", "1");//该用户至少发表评论多少条才会显示
	update_option("qw_RW_days", "180");//统计多少天内的评论
}

function qw_RW_act() {
	add_option("qw_RW_css", "");//保存css样式文件名
	add_option("qw_RW_manualcss", "");//保存自定义css样式内容
	add_option("qw_RW_shortcode", "");//保存文章调用时的短代码
	add_option("qw_RW_shownumber", "");//展示评论数排名前多少的用户
	add_option("qw_RW_commentatleast", "");//该用户至少发表评论多少条才会显示
	add_option("qw_RW_days", "");//统计多少天内的评论
	qw_RW_setdefault();
}

function qw_RW_deact() {
	delete_option("qw_RW_css");
	delete_option("qw_RW_manualcss");
	delete_option("qw_RW_shortcode");
	delete_option("qw_RW_shownumber");
	delete_option("qw_RW_commentatleast");
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
		update_option("qw_RW_commentatleast", $_POST['qw_RW_commentatleast']);
		update_option("qw_RW_days", $_POST['qw_RW_days']);
		echo "<div id=\"message\" class=\"rwupdate\"><p>更新保存成功，请在文章中插入[" . get_option("qw_RW_shortcode") . "]启用读者墙</p></div>";
	}
	if($_POST['qw_RW_resetbtn']=="恢复默认设置") {
		qw_RW_setdefault();
		echo "<div id=\"message\" class=\"rwupdate\"><p>恢复成功，请在文章中插入[" . get_option("qw_RW_shortcode") . "]启用读者墙</p><p>自定义css样式不会清除；如欲重置，手动清空右下侧单元格您输入的内容即可</p></div>";
	}
	if($_POST['qw_RW_savebtn']=="保存") {
		update_option("qw_RW_manualcss", $_POST['qw_RW_manualcss']);
		echo "<div id=\"message\" class=\"rwupdate\"><p>自定义css文件保存成功，请再手动点击一次“更新按钮”，以确认启用</p></div>";
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

<table border="1">
<td>
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
					<th scope="row">最少评论数</th>
					<td>
						<input class="regular-text" name="qw_RW_commentatleast" type="text" id="qw_RW_commentatleast" value="<?php echo get_option("qw_RW_commentatleast"); ?>" />
						<p>该用户至少发表评论多少条才会显示？</p>
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
						<input type="radio" id="animated_with_css3.css" name="qw_RW_css" value="animated_with_css3.css" checked="checked" onclick="qwhide()" />缩放动画<br>
						<input type="radio" id="animated_shift.css" name="qw_RW_css" value="animated_shift.css" onclick="qwhide()" />偏移动画<br>
						<input type="radio" id="myjquery.js" name="qw_RW_css" value="myjquery.js" onclick="qwhide()" disabled />jquery版本敬请期待<br>
						<input type="radio" id="manualcss" name="qw_RW_css" value="manualcss" onclick="qwshow()"/>自定义css<br>
						<textarea id="temptemptemp" style="display:none;"><?php echo get_option("qw_RW_css");?></textarea>
						<p>如果选择自定义css，请确保输入的css正确无误</p>
					</td>
				</tr>
			</tbody>
		</table>
		<input type="submit" name="qw_RW_btn" value="更新" class="button-primary" />
		<input type="submit" name="qw_RW_resetbtn" value="恢复默认设置" class="button-primary" />
	</form></div>
</td>
<td id="manualarea" style="display:none;">
	<form method="post">
	自定义css文件编辑区
	<textarea name="qw_RW_manualcss" rows="23" cols="50" id="qw_RW_manualcss" class="large-text code"><?php echo get_option('qw_RW_manualcss'); ?></textarea>
	<input type="submit" name="qw_RW_savebtn" value="保存" class="button-primary" />
	</form>
</td>
</table>
						<SCRIPT LANGUAGE="JavaScript">
							function qwshow(){
								a=document.getElementById('manualarea').style.display="";
							}
							function qwhide(){
								document.getElementById('manualarea').style.display="none";
							}
							b=document.getElementById('temptemptemp').value;
							c=document.getElementById(b);
							c.checked="TRUE";
							c.click();
						</script>

	<div style="display:none;"><hr>
	<iframe frameborder="0" src="http://blog.leniy.org/readers-wall.html" scrolling="auto" noresize="" width="100%" height="500px"></iframe></div>
	<?php
}

/**********************************************************************/
/**********************   下面是读者墙显示函数   **********************/
/**********************************************************************/
add_shortcode(get_option("qw_RW_shortcode"), "qw_RW_page");

function qw_RW_page() {
	global $wpdb;
	$qw_RW_css = get_option('qw_RW_css');
	$qw_RW_shownumber = get_option('qw_RW_shownumber');
	$qw_RW_days = get_option('qw_RW_days');
	$qw_RW_commentatleast = get_option('qw_RW_commentatleast');
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
			AND comment_author_email NOT LIKE 'api@postlinks.com'
	) AS tempcmt
	GROUP BY comment_author_email
	ORDER BY number DESC
	LIMIT {$qw_RW_shownumber}
	";
	$RWs = $wpdb->get_results($query);
	foreach ($RWs as $RW) {
		if ($RW->number >= $qw_RW_commentatleast) {
			$temp_number = $RW->number;
			$temp_email = $RW->comment_author_email;
			$temp_author = $RW->comment_author;
			$temp_url = $RW->comment_author_url ? $RW->comment_author_url : "http://blog.leniy.org";
			$temp_avatar = get_avatar($RW, 50, plugins_url('readers-wall/resource/default.png'));
			$nofollow = "";

			if ($qw_RW_css == "animated_with_css3.css") {
				$output .= "<a title=\"" . $temp_author . "  (" . $temp_number . "条评论)\" href=\"" . $temp_url . "\" target=\"_blank\" class=\"RW-btn\" rel=\"" . $nofollow . "\">" . "<span class=\"RW-btn-slide-text\">" . $temp_number . "</span>" . $temp_avatar . "</a>";
			}
			if ($qw_RW_css == "animated_shift.css") {
				$output .= "<li><a target=\"_blank\" href=\"" . $temp_url . "\" rel=\"" . $nofollow . "\">" . $temp_avatar . "<em>" . $temp_author . "</em> <strong>" . $temp_number . "</strong></br></a></li>";
			}
			if ($qw_RW_css == "manualcss") {
				$output .= "<a title=\"" . $temp_author . "  (" . $temp_number . "条评论)\" href=\"" . $temp_url . "\" target=\"_blank\" class=\"RW-btn\" rel=\"" . $nofollow . "\">" . "<span class=\"RW-btn-slide-text\">" . $temp_number . "</span>" . $temp_avatar . "</a>";
			}

		}
	}
	$output = "<ul class=\"readers-wall\">" . $output . "</ul>";
	if ($qw_RW_css == "manualcss") {
		$css = '<style type="text/css">' . get_option("qw_RW_manualcss") . '</style>';
	}
	else {
		$css = '<link type="text/css" rel="stylesheet" href="' . plugins_url('readers-wall/style/' . $qw_RW_css) . '" />';
	}
	return $css . $output;
}


/**********************************************************************/
/**********************   下面开启博客压缩输出   **********************/
/**********************************************************************/

function gzippy() {
	ob_start('ob_gzhandler');
}

if(!stristr($_SERVER['REQUEST_URI'], 'tinymce') && !ini_get('zlib.output_compression')) {
	add_action('init', 'gzippy');
}


?>

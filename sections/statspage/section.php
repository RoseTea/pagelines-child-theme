<?php
/*
	Section: Stats Page
	Author: PageLines
	Author URI: http://www.pagelines.com
	Description: Loads Quest Page Stuff
	Class Name: StatsPage
	Workswith: templates, main, header, morefoot
*/

/**
 * Stats Page Section
 *
 * 
 *
 * @package PageLines Framework
 * @author PageLines
 */
class StatsPage extends PageLinesSection {

	/**
	* Section template.
	*/
	function section_template() { 
?>
<?php
global $wpdb;
$current_user = wp_get_current_user();
if(isset($_GET['id'])){$user_id = $_GET['id'];
$user_info = get_userdata($user_id);
$userlogin = $user_info->user_login ;
$gamertag = $user_info->display_name ;
$user_website= $user_info->user_url;
} else {$user_id = $current_user->ID; 
$userlogin = $current_user->user_login;
$gamertag = $current_user->display_name;
$user_website= $current_user->user_url;}

$quest_posts_ids = $wpdb->get_results("select post_id from wp_postmeta where meta_value = 'page.quest.php' and meta_key ='_wp_page_template'");
$quest_posts_count = (int) $wpdb->get_var("select count(*) from wp_postmeta where meta_value = 'page.quest.php' and meta_key ='_wp_page_template'");
$quests_e =  (int)$wpdb->get_var("select count(data) from wp_cp where uid = $user_id and status = 1");
$quests_a =  (int)$wpdb->get_var("select count(data) from wp_cp where uid = $user_id and status = 2");
$quests_c =  (int)$wpdb->get_var("select count(data) from wp_cp where uid = $user_id and status = 3");
$quests_m =  (int)$wpdb->get_var("select count(data) from wp_cp where uid = $user_id and status = 4");
$quests_ne = $quest_posts_count - $quests_e - $quests_a - $quests_c - $quests_m;

//Progres Bar!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 $testing = $wpdb->get_var('select option_value from wp_options where option_name = "cp_module_ranks_data"');
$new = unserialize($testing);
ksort($new);
$new_array=array_values($new);
reset($new);
$rank = cp_module_ranks_getRank($user_id);
$rank_points = array_search($rank,$new_array);
$current_rank_points = array_search($rank,$new);
$prev_lvl = $new_array[$rank_points];
$xp = cp_getPoints($user_id);
$current_lvl = array_search($next_lvl,$new);
$next_ranks_points = $new_array[$rank_points+1];
$xp_level =  array_search($next_ranks_points,$new);
?><input type="text" style="display:none;"  name="re_xp_in" value="<?php echo 8000; ?>" /></form>  <?php
if(isset($_POST['re_xp_in'])){$xp_left= $_POST['re_xp_in'];}else{$xp_left= $xp;}
$percentage_num = $xp_left - array_search($prev_lvl,$new);
$percentage_dom = $xp_level - $current_rank_points;
$percentage = $percentage_num/$percentage_dom*100;
if ($percentage <=1 ){$percentage = 1;}
reset($new);
$new = unserialize($testing);
ksort($new);


//Gold!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
$period_count_check_three = (int) $wpdb->get_var("select count(*) from wp_total_cur where id = $user_id and period_one != 0 and period_two != 0 and period_three != 0");
if($period_count_check_three != 0){$period_count = 3;}else{
$period_count_check_two = (int) $wpdb->get_var("select count(*) from wp_total_cur where id = $user_id and period_one != 0 and period_two != 0");
if($period_count_check_two != 0){$period_count = 2;}else{
$period_count = 1;
}}

$minutes_required = 10800*$period_count;


$querygold = "SELECT SUM(gold) FROM wp_class_cur where login = '$userlogin'"; 
$resultgold = mysql_query($querygold) or die(mysql_error());
$rowgold = mysql_fetch_array($resultgold);
$gold_rnd = $rowgold['SUM(gold)'];
$queryminutes = "SELECT SUM(minutes) FROM wp_class_cur where login = '$userlogin'"; 
$resultminutes = mysql_query($queryminutes) or die(mysql_error());
$rowminutes = mysql_fetch_array($resultminutes);
$minutes_rnd = $rowminutes['SUM(minutes)'];
$wpdb->update( $table_name_c, array( 'totalgold' => $gold_rnd/*,'totalsilver' => $silver_sb, 'totalcopper' => $copper_rnd */), array(login => $userlogin ), array( '%d','%d'), array( '%s' ));
$qry_m="select minutes, minutes_reason, timestamp from wp_class_cur where login = '$userlogin' and minutes IS NOT NULL order by id desc ";  
$qry_g="select gold, gold_reason, timestamp from wp_class_cur where login = '$userlogin' and gold IS NOT NULL order by id desc";
$qry_xp="select type, data, points from wp_cp where uid = '$user_id' order by id desc";  
$qry_per="select period_one, period_two, period_three, computer_one, computer_two, computer_three from wp_total_cur where login = '$userlogin'";  
$rt_m=mysql_query($qry_m);
$rt_g=mysql_query($qry_g);
$rt_xp=mysql_query($qry_xp);
$rt_per=mysql_query($qry_per);
$minutes_percentage = $minutes_rnd/$minutes_required*100;



//leaderboards
$xp_lbs= $wpdb->get_results("select id, totalxp from wp_total_cur order by totalxp DESC limit 0, 30");




?> 
 <head>
<link rel="stylesheet" href="/wp-content/themes/<?php echo  get_stylesheet(); ?>/sections/statspage/myown_js/ui-lightness/jquery-ui-1.8.21.custom.css" type="text/css" />
<link rel="stylesheet" type="text/css" href="/wp-content/themes/<?php echo  get_stylesheet(); ?>/sections/statspage/myown_js/myown_style.css" /> 

</head>
<body>
<div id="topinfo">
<h2 id="gt"><?php echo $gamertag; ?></h2>
<div id="gravatarstat">
<?php
echo '<div id="re_xp_p"></div>';
echo get_avatar( $user_id, '96',  $default = '<path_to_url>'); 
?>
</div>
<div id="profileinfo">
<table cellpadding="0px" id="profiletable">
<tr><td width="150px"><a href="<?php echo $user_website; ?>" target="_blank" >Website</a></td><td width="150px">Ecountered: <?php echo $quests_e+$quests_a+$quests_c+$quests_m; ?></td><tr>
<tr><td><?php echo $rank; ?> </td><td> Accepted: <?php echo $quests_a+$quests_c+$quests_m;?></td></tr>
<tr><td> <?php print $xp_left.'/'.$xp_level;?></td><td> Completed: <?php echo $quests_c+$quests_m; ?></td></tr>
<tr><td> <?php print $gold_rnd; ?> Gold </td><td> Mastered: <?php echo $quests_m; ?></td></tr>
<tr><td> <?php echo $minutes_rnd; ?>/<?php echo $minutes_required; ?> Minutes</td><td>Not Encountered: <?php echo $quests_ne;  ?></td><tr>
</table>
</div>
<div id="progressbar">
	<?php
		
	 print "<div id=\"progress-bar-2\" class=\"all-rounded\">\n";
	print "<div id
	=\"progress-bar-percentage-stats-2\" class=\"all-rounded\" style=\"
	-webkit-border-radius: 5px;
    -moz-border-radius: 5px;
    border-radius: 5px;
	border-bottom-width:100%px; 
	height:20px;
	padding: 5px 0px;
 	color: #FFF;
 	font-weight: bold;
	background-color:#ff6600;
	
background: -webkit-gradient(linear, left top, left bottom, 
color-stop(0%, #ff6600), color-stop(20%, #ff6600), color-stop(150%, rgba(255,255,255,.9))); 

background: -webkit-linear-gradient(top, #ff6600, rgba(255,255,255,.9)); 
background: -moz-linear-gradient(top, #ff6600 0%, #ff6600 20%, rgba(255,255,255,.9) 150%);
 	text-align: center; width: ".$percentage."%\">";
	print "</div></div>";
	print $xp_left.'/'.$xp_level;
	 ?>
     </div>
     <div id="progressbar2">
	<?php
		
	 print "<div id=\"progress-bar-3\" class=\"all-rounded\">\n";
	 ?>
     
     <div id="time-indicator">
     <div id="ti"> | | | | | 
     </div>
     </div>
     <?php
	print "<div id
	=\"progress-bar-percentage-stats-3\" class=\"all-rounded\" style=\"
	-webkit-border-radius: 5px;
    -moz-border-radius: 5px;
    border-radius: 5px;
	border-bottom-width:100%px; 
	height:20px;
	padding: 5px 0px;
 	color: #FFF;
 	font-weight: bold;
	background-color:#486fb3;
	
background: -webkit-gradient(linear, left top, left bottom, 
color-stop(0%, #486fb3), color-stop(20%, #486fb3), color-stop(150%, rgba(255,255,255,.9))); 
background: -webkit-linear-gradient(top, #486fb3, rgba(255,255,255,.9)); 
background: -moz-linear-gradient(top, #486fb3 0%, #486fb3 20%, rgba(255,255,255,.9) 150%);
 	text-align: center; width: ".$minutes_percentage."%\">";
	print "</div></div>";
	print $minutes_rnd.'/'.$minutes_required;
	 ?>
  
 </div>
    </div>

<div id ="tabs">
<ul>
<li><a href="#quests">Quests</a></li>
<li><a href="#xp">XP</a></li>
<li><a href="#minutes">Minutes</a></li>
<li><a href="#gold">Gold</a></li>
<li><a href="#log">Log-ins</a></li>
<li><a href="#xp_lb">XP LB</a></li>

</ul>
<div id="log">
<?php

/*$log_list = $wpdb->get_results("select i_datetime from wp_3wp_activity_monitor_index where user_id = $user_id and activity_id = 'wp_login' order by i_id desc");*/
$gold_login_history = $wpdb->get_var("select logins from wp_class_cur_logintracker where uid = $user_id ");

$loglist = explode('~', $gold_login_history);
foreach ($loglist as $lg_list){echo $lg_list.'<br/>';}

?>
</div>
<div id="xp">
<table id="xp_table" rules="rows">
<tr><th>Type</th><th>Reason</th><th>XP</th></tr>
<?php $j =1; while($nt=mysql_fetch_array($rt_xp)){$xp_div_id = ($j&1) ? "odd" : "even";$data = $nt['data'];$type = $nt['type'];$xp_p = $nt['points'];
$xp_quest_check = (int) $wpdb->get_var("select count(*) from wp_cp where data ='$data' and uid = $user_id and type = 'Quest'");
if($xp_quest_check != 0){ 
$xp_post_id = $wpdb->get_var("select post_parent from wp_posts where post_title = '$data' order by id desc limit 1");
$xp_post_id_link = get_permalink($xp_post_id);
echo'<tr id="'.$xp_div_id.'"><th>'.$type.'</th><th><a href="'.$xp_post_id_link.'">'.$data.'</a></th><th>'.$xp_p.'</th></tr>';
}else{
 echo'<tr id="'.$xp_div_id.'"><th>'.$type.'</th><th>'.$data.'</th><th>'.$xp_p.'</th></tr>';} $j= $j+1;} ?>
</table>
</div>
<div id="minutes">
<?php $v = 1; while($nt=mysql_fetch_array($rt_m)){  $minutes_div_id = ($v&1) ? "odd" : "even";$minutes = $nt['minutes']; $minutes_reason = $nt['minutes_reason']; echo '<div id="'.$minutes_div_id.'">'.$minutes.'-'.$minutes_reason.' - '.$nt['timestamp'].'</div>'; $v = $v+1;}  ?>
</div>
<div id="gold"
><?php $g = 1; while($nt=mysql_fetch_array($rt_g)){
	$gold_div_id = ($g&1) ? "odd" : "even";
	 $gold_d = $nt['gold']; 
	 $gold_reason_d = $nt['gold_reason'];
$gold_check_master = (int) $wpdb->get_var("select count(*) from wp_cp where data ='$gold_reason_d' and uid = $user_id and status in(4,5)");
$gold_check_pc = (int)$wpdb->get_var("select count(*) from wp_class_cur where gold_reason ='$gold_reason_d' and login = '$userlogin' and status = 5");
if($gold_check_master != 0){ 
$gold_post_id = $wpdb->get_var("select post_parent from wp_posts where post_title = '$gold_reason_d' order by id desc limit 1");
$gold_post_id_link = get_permalink($gold_post_id);
echo '<div id= "'.$gold_div_id.'">'.$gold_d.' - <a href="'.$gold_post_id_link.'" >'.$gold_reason_d.'</a> - '.$nt['timestamp'].'</div>';} else if($gold_check_pc != 0){$gold_post_id = $wpdb->get_var("select post_parent from wp_posts where post_title = '$gold_reason_d' order by id desc limit 1");
$gold_post_id_link = get_permalink($gold_post_id);
echo '<div id= "'.$gold_div_id.'">'.$gold_d.' - <a href="'.$gold_post_id_link.'" >'.$gold_reason_d.'</a> - '.$nt['timestamp'].'</div>';} else{
 echo '<div id= "'.$gold_div_id.'">'.$gold_d.' - '.$gold_reason_d.' - '.$nt['timestamp'].'</div>'; }$g = $g+1;} ?>
</div>
<div id="quests">
<?php
$quests= $wpdb->get_results("SELECT id FROM wp_cp WHERE  status != 0 and uid = $user_id order by id desc");	
?>    <table> <tr align="left"><th> Encountered</th><th> Accepted</th><th> Completed</th><th> Mastered</th></tr><?php
$s = 1;
foreach ($quests as $qst){foreach($qst as $qsts){
	
$encountered= $wpdb->get_var("SELECT data FROM wp_cp WHERE  status = 1 and uid = $user_id and id = $qsts");
$accepted= $wpdb->get_var("SELECT data FROM wp_cp WHERE  status = 2 and uid = $user_id and id = $qsts");
$completed= $wpdb->get_var("SELECT data FROM wp_cp WHERE  status = 3 and uid = $user_id and id = $qsts");
$mastered= $wpdb->get_var("SELECT data FROM wp_cp WHERE  status = 4 and uid = $user_id and id = $qsts");

			
$encountered_t= $wpdb->get_var("SELECT data FROM wp_cp WHERE  (status = 1 or status = 2 or status = 3 or status = 4) and uid = $user_id and id = $qsts");
$accepted_t= $wpdb->get_var("SELECT data FROM wp_cp WHERE  ( status = 2 or status = 3 or status = 4) and uid = $user_id and id = $qsts");
$completed_t= $wpdb->get_var("SELECT data FROM wp_cp WHERE  ( status = 3 or status = 4) and uid = $user_id and id = $qsts");
$mastered_t= $wpdb->get_var("SELECT data FROM wp_cp WHERE  status = 4 and uid = $user_id and id = $qsts");
			
			
$encountered_time= $wpdb->get_var("SELECT enc FROM wp_class_cur_log WHERE uid = $user_id and (quest ='$encountered' or quest = '$accepted' or quest = '$completed' or quest = '$mastered')");	
$accepted_time= $wpdb->get_var("SELECT acc FROM wp_class_cur_log WHERE  uid = $user_id and (quest ='$encountered' or quest = '$accepted' or quest = '$completed' or quest = '$mastered')");	
$completed_time= $wpdb->get_var("SELECT com FROM wp_class_cur_log WHERE  uid = $user_id and (quest ='$encountered' or quest = '$accepted' or quest = '$completed' or quest = '$mastered')");
$mastered_time= $wpdb->get_var("SELECT mas FROM wp_class_cur_log WHERE  uid = $user_id and (quest ='$encountered' or quest = '$accepted' or quest = '$completed' or quest = '$mastered')");				
			
$title= $wpdb->get_var("SELECT data FROM wp_cp where id = $qsts");	
$ids = (int)$wpdb->get_var("SELECT post_parent FROM wp_posts WHERE  post_title = '$title'  order by id desc limit 1");
$permalink = get_permalink( $ids );
						 
						 ?>
                        
                       <tr id= "<?php echo ($s&1) ? "odd" : "even"; ?>"> <td align="left" title="<?php echo $encountered_time;?>"><a href="<?php echo $permalink; ?>"><?php echo $encountered_t;?> </a> </td> 
					<td align="left" title="<?php echo $accepted_time; ?>"> <a href="<?php echo $permalink; ?>"><?php echo $accepted_t;?></a> </td>
                     <td  align="left" title="<?php echo $completed_time; ?>"> <a href="<?php echo $permalink; ?>"><?php echo $completed_t;?> </a> </td>
                 <td align="left" title=" <?php echo $mastered_time; ?>"> <a href="<?php echo $permalink; ?>"><?php echo $mastered_t;?> </a> </td> </tr> <?php  $s = $s+1;  }}  ?>
                         
                         </table>
                         
</div>
<div id="xp_lb">
<?php

$xp_int = 1;
	?>
	<table>
    <tr><th align="left">#</th><th align="left">Gamer Tag</th><th align="left">XP</th></tr>
    <?php
foreach($xp_lbs as $xp_lb){

	$xp_id = $xp_lb->id;
	$xp_user_info = get_userdata($xp_id);
$xp_gamertag = $xp_user_info->display_name ;
	$xp_lb_xp = $xp_lb->totalxp; 
    echo '<tr><td>'.$xp_int.'</td><td>'.$xp_gamertag.'</td><td>'.$xp_lb_xp.'</td></tr>';
	$xp_int = $xp_int + 1;
	
	

	}


	
	?>
    </table>
	<?php


?>                         
</div>


 </div>
 
 <script src="/wp-content/themes/<?php echo  get_stylesheet(); ?>/sections/statspage/myown_jquery.js"></script>
<script src="/wp-content/themes/<?php echo  get_stylesheet(); ?>/sections/statspage/myown_js/jquery-ui.js"></script>
<script src="/wp-content/themes/<?php echo  get_stylesheet(); ?>/sections/statspage/myown_js/development-bundle/external/jquery.cookie.js"></script>
<script src="/wp-content/themes/<?php echo  get_stylesheet(); ?>/sections/statspage/myown_js/ui.js"></script>



 </body>
		
	


		<?php do_action( 'bp_after_blog_page' ) ?>

		</div><!-- .padder -->
	</div><!-- #content -->


	<?php
		
	}
}
?>
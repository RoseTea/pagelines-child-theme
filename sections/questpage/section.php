<?php
/*
	Section: Quest Page
	Author: PageLines
	Author URI: http://www.pagelines.com
	Description: Loads Quest Page Stuff
	Class Name: QuestPage
	Workswith: templates, main, header, morefoot
*/

/**
 * Quest Page Section
 *
 * 
 *
 * @package PageLines Framework
 * @author PageLines
 */
class QuestPage extends PageLinesSection {

	/**
	* Section template.
	*/
	function section_template() { 
?>
		<?php get_header() ?>

			<?php  get_sidebar() ?>
	<div class="content">
		<div class="padder">

  <script src="http://code.jquery.com/jquery-latest.js"></script>
  <script>
  $(document).ready(function(){
   setTimeout(function(){
  $("div.noticetime").fadeOut("slow", function () {
  $("div.noticetime").remove();
      });
    
}, 2000);
 });
  </script>

		<?php do_action( 'bp_before_blog_page' ) ?>
		

			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

				<h2 class="pagetitle"><?php the_title(); ?></h2>

				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>> <?php
			$sql="alter table wp_cp add column status INT";
$rt=mysql_query($sql);  
global $wpdb;
if (!is_user_logged_in()) {
	$page_id = get_the_ID();
$current_user = wp_get_current_user();
$page_title = get_the_title($page_id);
$page_id_manual = $wpdb->get_var("SELECT post_parent FROM wp_posts WHERE post_title = '$page_title' order by id desc limit 1 ");
$table_name = $wpdb->prefix . "cp" ;
$table_name_meta = $wpdb->prefix."postmeta" ;
global $current_user;
$user_id = $current_user->ID;
$sum = $wpdb->get_var("SELECT meta_value FROM wp_postmeta WHERE meta_key='sum' and post_id= '$page_id_manual' ");
echo $sum;
echo'<div class="entry">';

						 the_content( __( '<p class="serif">Read the rest of this page &rarr;</p>', 'buddypress' ) ); ?>

						<?php wp_link_pages( array( 'before' => '<div class="page-link"><p>' . __( 'Pages: ', 'buddypress' ), 'after' => '</p></div>', 'next_or_number' => 'number' ) ); ?>
						<?php edit_post_link( __( 'Edit this page.', 'buddypress' ), '<p class="edit-link">', '</p>'); 
					
	}else{
$page_id = get_the_ID();
$current_user = wp_get_current_user();
$page_title = get_the_title($page_id);
$page_id_manual = $wpdb->get_var("SELECT post_parent FROM wp_posts WHERE post_title = '$page_title' order by id desc limit 1 ");
$table_name = $wpdb->prefix . "cp" ;
$table_name_meta = $wpdb->prefix."postmeta" ;
global $current_user;
$user_id = $current_user->ID;
$sum = $wpdb->get_var("SELECT meta_value FROM wp_postmeta WHERE meta_key='sum' and post_id= '$page_id_manual' ");
echo $sum;
$test = (int) $wpdb->get_var("SELECT count(*) FROM $table_name WHERE uid = '$user_id' AND  data = '$page_title' ");
$points = cp_getPoints($user_id);
if ($test == 0 ) {
	$test_b = (int) $wpdb->get_var("SELECT count(*) FROM $table_name WHERE uid = '$user_id' AND  data = '$page_title' ");
if ($test == 0 ) {
global $wpdb;
$page_id = get_the_ID();
$page_title = get_the_title($page_id);
$table_name_meta = $wpdb->prefix."postmeta" ;
global $current_user;
$current_user = wp_get_current_user();
$user_id = $current_user->ID;
$xp_value_e = $wpdb->get_var("SELECT meta_value FROM wp_postmeta WHERE post_id = '$page_id' AND meta_key =  'xp_e'");
echo '<div class="noticetime"><div class="notice-wrap"><div class="notice-item-wrapper"><div style="" class="notice-item notice"><div class="notice-item-close">×</div><p>+'.$xp_value_e.'</p></div></div></div></div>';

$time = date('m/d@H:i',current_time('timestamp',0));
$wpdb->insert( 'wp_cp', array(  'timestamp' => time(), 'uid' => $user_id,'points' => $xp_value_e, 'data' => $page_title,'type' => "Quest" , 'status'=>1), array( '%s','%s','%s')); 
$wpdb->insert( 'wp_class_cur_log', array( 'uid' => $user_id, 'quest' => $page_title, 'enc'=> $time  ), array( '%s','%s','%s'));
	update_user_meta($user_id, 'cpoints', $points+$xp_value_e);
}}

$stat= (int) $wpdb->get_var("SELECT status FROM wp_cp WHERE uid = '$user_id' AND  data = '$page_title' ");

if(isset($gold_clicked)){$stat=2;}


$gold_clicked = $_POST['pc_gold_b'];
if(isset($gold_clicked)){
	$stat_a= (int) $wpdb->get_var("SELECT status FROM wp_cp WHERE uid = '$user_id' AND  data = '$page_title' ");
	if($stat_a == 1){
global $wpdb;
$page_id = get_the_ID();
$page_title = get_the_title($page_id);
$current_user = wp_get_current_user();
$user_id = $current_user->ID;
$time = date('m/d@H:i',current_time('timestamp',0));
$xp_value_a = $wpdb->get_var("SELECT meta_value FROM wp_postmeta WHERE post_id = '$page_id' AND meta_key =  'xp_a'");
$xp_value_e = $wpdb->get_var("SELECT meta_value FROM wp_postmeta WHERE post_id = '$page_id' AND meta_key =  'xp_e'");
$xp_value_ae  = $xp_value_a - $xp_value_e;
echo '<div class="noticetime"><div class="notice-wrap"><div class="notice-item-wrapper"><div style="" class="notice-item notice"><div class="notice-item-close">×</div><p>+'.$xp_value_ae.'</p></div></div></div></div>';
$wpdb->update( 'wp_cp', array(  'timestamp' => time(), 'points' => $xp_value_a,'type' => "Quest", 'data'=> $page_title,  'status'=>2 ), array(uid => $user_id, data => $page_title),array( '%s','%s'));
$wpdb->update( 'wp_class_cur_log', array( 'acc'=>$time), array(uid => $user_id, quest => $page_title),array( '%s','%s'));
$stat=2;
update_user_meta($user_id, 'cpoints', $points+$xp_value_ae);
}
}

		  $button_clik = $_POST['add_button'];
if(isset($button_clik)){ 
$stat_c= (int) $wpdb->get_var("SELECT status FROM wp_cp WHERE uid = '$user_id' AND  data = '$page_title' ");
	if($stat_c == 2){
global $wpdb;
$page_id = get_the_ID();
$page_title = get_the_title($page_id);
$current_user = wp_get_current_user();
$user_id = $current_user->ID;
$time =date('m/d@H:i',current_time('timestamp',0));
$test_c = (int) $wpdb->get_var("select count(*) from wp_cp where uid= $user_id and data = '$page_title' and status = 3");
$xp_value_c = $wpdb->get_var("SELECT meta_value FROM wp_postmeta WHERE post_id = '$page_id' AND meta_key =  'xp_c'");
$wpdb->update( 'wp_cp', array(  'timestamp' => time(), 'points' => $xp_value_c,'type' => "Quest",  'status'=>3 ), array(uid => $user_id, data => $page_title),array( '%s','%s'));
$wpdb->update( 'wp_class_cur_log', array( 'com'=> $time ), array(uid => $user_id, quest => $page_title),array( '%s','%s'));
$xp_value_a = $wpdb->get_var("SELECT meta_value FROM wp_postmeta WHERE post_id = '$page_id' AND meta_key =  'xp_a'");
$xp_value_ce  = $xp_value_c - $xp_value_a;
echo '<div class="noticetime"><div class="notice-wrap"><div class="notice-item-wrapper"><div style="" class="notice-item notice"><div class="notice-item-close">×</div><p>+'.$xp_value_ce.'</p></div></div></div></div>';
$stat=3;
update_user_meta($user_id, 'cpoints', $points+$xp_value_ce);}}
$button_master= $_POST['master'];
if(isset($button_master)){ 
$stat_m= (int) $wpdb->get_var("SELECT status FROM wp_cp WHERE uid = '$user_id' AND  data = '$page_title' ");
	if($stat_m == 3){
global $wpdb;
$userlogin = $current_user->user_login;
$page_id = get_the_ID();
$page_title = get_the_title($page_id);
$current_user = wp_get_current_user();
$user_id = $current_user->ID;
$test_m = (int) $wpdb->get_var("select count(*) from wp_cp where uid= $user_id and data = '$page_title' and status = 4");
$time = date('m/d@H:i',current_time('timestamp',0));
$gold_value_m = (int)$wpdb->get_var("SELECT meta_value FROM wp_postmeta WHERE post_id = '$page_id' AND meta_key =  'gold_m'");
$xp_value_c = $wpdb->get_var("SELECT meta_value FROM wp_postmeta WHERE post_id = '$page_id' AND meta_key =  'xp_c'");
$xp_value_m = $wpdb->get_var("SELECT meta_value FROM wp_postmeta WHERE post_id = '$page_id' AND meta_key =  'xp_m'");
$xp_value_me  = $xp_value_m - $xp_value_c;
echo '<div class="noticetime"><div class="notice-wrap"><div class="notice-item-wrapper"><div style="" class="notice-item notice"><div class="notice-item-close">×</div><p>+'.$xp_value_me.'</p></div></div></div></div>';
$wpdb->update( 'wp_cp', array(  'timestamp' => time(), 'type' => "Quest",  'status'=>4, 'points' => $xp_value_m ), array(uid => $user_id, data => $page_title),array( '%s','%s'));
$wpdb->update( 'wp_class_cur_log', array( 'mas'=> $time ), array(uid => $user_id, quest => $page_title),array( '%s','%s'));
$wpdb->insert( 'wp_class_cur', array(  'gold' => $gold_value_m,'gold_reason' => $page_title, 'status'=>4, 'login' => $userlogin, 'timestamp' => $time),  array( '%s','%s',));
update_user_meta($user_id, 'cpoints', $points+$xp_value_me);
$stat=4;
echo '<embed src ="/wp-content/plugins/cube-gold/CashRegister.mp3" hidden="true" autostart="true"></embed>';}}
if($stat==1){
	$value='I Accept This Quest';
	$accept='<form method="post" action=""> <input name="pc_gold_b" type="submit" value="'.$value.'"/> </form>';
echo $accept;
} else {

echo'<div class="entry">';

						 the_content( __( '<p class="serif">Read the rest of this page &rarr;</p>', 'buddypress' ) ); ?>

						<?php wp_link_pages( array( 'before' => '<div class="page-link"><p>' . __( 'Pages: ', 'buddypress' ), 'after' => '</p></div>', 'next_or_number' => 'number' ) ); ?>
						<?php edit_post_link( __( 'Edit this page.', 'buddypress' ), '<p class="edit-link">', '</p>'); 
						?>

   <?php if($stat==2){ 
			?>

<form method="post" action="">
<input type="submit" value="Complete Quest" name="add_button">
</form>

			<?php 
	
}
   
   if($stat==3){ 
?>

<form method="post" action="">
<input type="submit" value="Master Quest" name="master">
</form>


			<?php 
			  
} ?> 
      <?php }} echo '<br />';
	  comments_template(); ?>

<?php  endwhile; endif; ?>

		
	<?php  do_action( 'bp_after_blog_page' ) ?>

        </div><!-- .padder -->

	</div><!-- #content -->
</div>

    <br />
    </div>
    <?php get_footer(); ?>
	<?php	}}
	
	
	
	 ?>
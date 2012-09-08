<?php
/*
	Section: Paid Content
	Author: PageLines
	Author URI: http://www.pagelines.com
	Description: Loads Paid Content
	Class Name: PaidContent
	Workswith: templates, main, header, morefoot
*/

/**
 * Paid Content Section
 *
 * 
 *
 * @package PageLines Framework
 * @author PageLines
 */
class PaidContent extends PageLinesSection {

	/**
	* Section template.
	*/
	function section_template() { 
?>
		<?php get_header() ?>

			<?php  get_sidebar() ?>
	<div id="content">
		<div class="padder">

		<?php do_action( 'bp_before_blog_page' ) ?>	

		<div class="page" id="blog-page" role="main">
	
			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

				<h2 class="pagetitle"><?php the_title(); ?></h2>

				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>> <?php
global $wpdb;
$page_id = get_the_ID();
$page_title = get_the_title($page_id);
$table_name = $wpdb->prefix . "class_cur" ;
$table_name_meta = $wpdb->prefix."postmeta" ;
$time = date('m/d@H:i',current_time('timestamp',0));
$current_user = wp_get_current_user();
$userlogin = $current_user->user_login;
$pgold_value = (int) $wpdb->get_var("SELECT meta_value FROM wp_postmeta WHERE post_id = '$page_id' AND meta_key = 'gold_m'");
$gold_value = -$pgold_value;
$test = (int) $wpdb->get_var("SELECT count(*) FROM $table_name WHERE login = '$userlogin' AND  gold_reason = '$page_title' ");
if ($test == 0 ) {
$wpdb->insert( $table_name, array(  'gold_reason' => $page_title, 'login' => $userlogin), array( '%s','%s',));}
$test_paid = (int) $wpdb->get_var("SELECT count(*) FROM $table_name WHERE login = '$userlogin' AND  gold_reason = '$page_title'  and status = '5'");
$gold_clicked = $_POST['pc_gold_b'];
if(isset($gold_clicked)){
	$gold_sum= (int) $wpdb->get_var("select sum(gold) from wp_class_cur where login = '$userlogin'");
	if($gold_sum >= $pgold_value){
	$wpdb->update( $table_name, array('gold' => $gold_value,'status' => 5,'gold_reason' => $page_title, 'login' => $userlogin, 'timestamp' => $time),array(gold_reason => $page_title, login => $userlogin),  array( '%s','%s',));$test_paid = 1;} else {echo' <div id="NSF" style="color:#F00"><strong>Insufficient Funds </strong></div>'; }}
if($test_paid == 0){
$summary = $wpdb->get_var("SELECT meta_value FROM wp_postmeta WHERE post_id = '$page_id' AND meta_key =  'sum'");
echo $summary;
echo '<form method="post" action=""> <input name="pc_gold_b" type="submit" value="Buy for '.$pgold_value.' Gold" /> </form>';

} else {
					
				
			
					echo '<div class="entry">';

						 the_content( __( '<p class="serif">Read the rest of this page &rarr;</p>', 'buddypress' ) ); ?>

						<?php wp_link_pages( array( 'before' => '<div class="page-link"><p>' . __( 'Pages: ', 'buddypress' ), 'after' => '</p></div>', 'next_or_number' => 'number' ) ); ?>
						<?php edit_post_link( __( 'Edit this page.', 'buddypress' ), '<p class="edit-link">', '</p>');} ?>

					</div>

				</div>

			<?php comments_template(); ?>

			<?php  endwhile; endif; ?>

		</div><!-- .page -->

		<?php  do_action( 'bp_after_blog_page' ) ?>

		</div><!-- .padder -->
	</div><!-- #content -->

	<style>
	#sidebar {
		float:right !important;
	}
	</style>
</div>
<?php get_footer(); ?>

	<?php
		
	}
}
?>
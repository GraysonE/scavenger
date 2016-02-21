<?php
/*
Template Name: Copy Followers
*/
?>

<?php get_template_part( 'templates/page-header' ); ?>

<?php while ( have_posts() ) : the_post(); ?>
<?php get_template_part( 'templates/content', 'copy-followers' ); ?>
<?php endwhile; ?>
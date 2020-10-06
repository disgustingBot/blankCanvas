<?php get_header(); ?>

<h1>archive.php</h1>

<?php while(have_posts()){the_post(); ?>

  <a href="<?php the_permalink(); ?>">
    <h3><?php the_title(); ?></h3>
  </a>
  <?php the_excerpt(); ?>


<?php } ?>

<?php get_footer(); ?>

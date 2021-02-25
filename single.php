<?php get_header(); ?>


<!-- colocar aqui el bloque del encabezado -->


<?php while(have_posts()){the_post(); ?>
  <h1><?php the_title(); ?></h1>
  <section class="main">
    <?php the_content(); ?>
  </section>
<?php } ?>


<?php get_footer(); ?>

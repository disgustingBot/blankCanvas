<?php get_header(); ?>


<!-- colocar aqui el bloque del encabezado -->
<h1>single.php</h1>


<?php while(have_posts()){the_post(); ?>
    <section class="main">
        <?php the_content(); ?>
    </section>
<?php } ?>


<?php get_footer(); ?>

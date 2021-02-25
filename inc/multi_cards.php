
<?php function simpla_card ($args = array()) {
    if(!isset($args['title']  )){ $args['title']   = get_the_title(); }
    if(!isset($args['link']   )){ $args['link']    = get_the_permalink(); }
    if(!isset($args['image']  )){ $args['image']   = get_the_post_thumbnail_url(); }
    if(!isset($args['excerpt'])){ $args['excerpt'] = get_the_excerpt(); }
    if(!isset($args['color']  )){ $args['color']   = get_post_meta(get_the_ID(), 'color', true); }
    ?>

    <a class="simpla" href="<?php echo $args['link']; ?>">
        <?php if($args['image'] != false){ ?>
            <div class="simpla_amg">
                <img class="simpla_img" loading="lazy" src="<?php echo $args['image']; ?>" alt="">
            </div>
        <?php } ?>
        <?php if($args['title'] != false){ ?>
            <h6 class="simpla_title font_size_6 row2col1"><?php echo $args['title']; ?></h6>
        <?php } ?>
        <?php if($args['color'] != false){ ?>
            <div class="simpla_deco" style="color:<?php echo $args['color']; ?>"></div>
        <?php } ?>
        <?php if($args['excerpt'] != false){ ?>
            <div class="simpla_txt font_size_7"><?php echo $args['excerpt']; ?></div>
        <?php } ?>
    </a>

<?php } ?>

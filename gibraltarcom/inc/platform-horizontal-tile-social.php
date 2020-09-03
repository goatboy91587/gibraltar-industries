<?php

$button = get_field('button');
if(have_rows('boxes_horizontal') || $button || $block_title || $block_text){ ?>
    <section class="atricles-block bg-light bg-extended horiztonal">
        <div class="posts-holder horizontal-tile">
            <?php while(have_rows('boxes_horizontal')){ the_row();
                $link = get_sub_field('link'); ?>
                <div class="horizontal-post">

                    <?php if($img = get_sub_field('image')){ ?>
                        <?php retina_image_html($img, '<div class="visual hoverArea">', '</div>', '(max-width: 639px)'); ?>
                    <?php } ?>
                    <div class="text-holder">
                        <h2>
                            <?php the_sub_field('title') ?>
                        </h2>
                        <div class="overlay">
                            <?php the_sub_field('text'); ?>

                            <?php if(isset($link['url']) && !empty($link['url'])){
                                $target = $link['target'] ? $link['target'] : '_self';
                                $btn_title = $link['title'] ? $link['title'] : __('Learn more', 'gibraltarcom');
                                $class = (is_page_template('pages/template-strategy.php')) ? 'more' : 'more visible-xs'; ?>
                                <a href="<?php echo $link['url']; ?>" target="<?php echo $target ?>" class="<?php echo $class; ?>"><span class="icon-arrow-right"></span></a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
        <?php if(isset($button['url']) && !empty($button['url'])){ ?>
            <div class="btn-holder text-center text-large">
                <a href="<?php echo $button['url']; ?>" class="btn-cta"><?php echo $button['name']; ?></a>
            </div>
        <?php } ?>
    </section>
<?php } ?>
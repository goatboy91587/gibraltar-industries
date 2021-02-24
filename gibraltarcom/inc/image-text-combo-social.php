<?php $block_headline = get_field('block_headline');
if(have_rows('image_text_combo') || $block_headline){ ?>
    <section class="bg-light image-text-combo">
        <?php if($block_headline){ ?>
            <header class="section-header text-center social-obligation">
                <?php if($block_headline){ ?><h2><?php echo $block_headline; ?></h2><?php } ?>
            </header>
        <?php } ?>
        <div class="combo">
            <?php while(have_rows('image_text_combo')){ the_row();
                $copy = get_sub_field('copy'); ?>
                <article class="article-post">
                    <?php if($image = get_sub_field('image')){
                        $image_1x = $image['image_1x'];
                        $image_2x = $image['image_2x'];
                        ?>
                        <picture>
                            <!--[if IE 9]><video style="display: none;"><![endif]-->
                            <source srcset="<?= $image_1x['url'] .', '. $image_1x['url']?> 2x" media="(max-width: 639px)">
                            <!--[if IE 9]></video><![endif]-->
                            <img src="<?=$image_1x['url']?>" alt="<?=$image_1x['alt']?>">
                        </picture>
                        <?php
                    } ?>
                    <div class="text-holder"><?php echo $copy ?></div>
                </article>
            <?php } ?>
    </section>
<?php } ?>
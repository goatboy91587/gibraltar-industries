<!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<?php wp_head(); ?>
	</head>
	<body <?php body_class(); ?>>
		<div class="wrapper">
			<?php $banner = get_banner_html(get_queried_object_id());
			$header_style = (is_page() || is_single()) && get_field('header_style', get_queried_object_id()) ? get_field('header_style', get_queried_object_id()) : 'bg-light';
			$header_bg = is_page() || is_single() ? get_field('bg_image', get_queried_object_id()) : false; ?>
			<?php if(is_page_template('pages/template-leadership.php') || is_page_template('pages/template-strategy.php') || is_page_template('pages/template-social.php')) echo '<div class="hero-block">'; ?>
			<header class="header <?php echo $header_style; ?>">
				<?php retina_image_html($header_bg, '<span class="bg-stretch">', '</span>', '(max-width: 639px)'); ?>
			  <div class="header-top <?php if($header_style == 'bg-dark' && !$header_bg ) echo 'has-bg'; ?> ">
				<div class="container">
					<?php if($header_style == 'bg-dark') $logo = get_field('logo', 'option');
					else $logo = get_field('logo_black', 'option');
					if($logo){ ?>
				  <div class="logo">
						<a href="<?php echo home_url(); ?>">							
							<img src="<?php echo $logo ?>" alt="<?php bloginfo( 'name' ); ?>">
						</a>
				  </div>
					<?php } ?>
				  <div class="nav-drop">
					<nav id="nav">
					  <?php if( has_nav_menu( 'primary' ) )
							wp_nav_menu( array(
								'container' => false,
								'theme_location' => 'primary',
								'menu_id'        => 'navigation',
								'menu_class'     => '',
								'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
								'walker'         => new Custom_Walker_Nav_Menu
								)
							); ?>
						<?php $sq = get_search_query() ? get_search_query() : __( 'Keyword', 'gibraltarcom' ); ?>
					  <form action="<?php echo home_url(); ?>" class="search-form search-box">
							<a href="#" class="search-opener"><span class="icon icon-search"></span></a>
							<div class="search-drop">
								<div class="form-group">
								<div class="input-wrap">
									<input type="search" name="s" class="form-control" placeholder="<?php echo $sq; ?>" value="<?php echo get_search_query(); ?>">
								</div>
								<input type="submit" value="<?php _e( 'Search', 'gibraltarcom' ); ?>">
								</div>
							</div>
					  </form>
					</nav>
				  </div>
				  <a href="#" class="nav-opener"><span><span class="sr-only">Menu Icon</span></span></a>
				</div>
			  </div>
				<?php if($banner) { ?>
					<div class="banner container">
						<?php if(function_exists('bcn_display_list') && !is_front_page())
						{ ?>
							<ol class="breadcrumb" typeof="BreadcrumbList" vocab="https://schema.org/">
								<?php bcn_display_list(); ?>
							</ol>
							<?php
						} ?>
						<?php echo $banner; ?>
					</div>
					<?php
				} ?>
			</header>
			<?php if(is_page_template('pages/template-leadership.php') || is_page_template('pages/template-strategy.php') || is_page_template('pages/template-social.php')){				
				if($top_blue_block = get_field('top_blue_block')){
					?>
					<section class="content-block container bg-dark bg-blue">
						<?php if(isset($top_blue_block['title']) && !empty($top_blue_block['title'])){ ?>
						<div class="section-header">
						  <h2><?php echo $top_blue_block['title'] ?></h2>
						</div>
						<?php } ?>
						<?php if(isset($top_blue_block['text']) && !empty($top_blue_block['text'])){ ?>
						<div class="content-holder">
						  <?php echo $top_blue_block['text'] ?>
						</div>
						<?php } ?>
					  </section>
					<?php
				}
				echo '</div>';
			} ?>
			<main class="main">
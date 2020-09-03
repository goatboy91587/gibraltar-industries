				<?php if(!is_page_template('pages/template-investors.php')) blue_block(); ?>
			</main>
			<footer class="footer text-small">
			  <div class="container">
				<?php if( has_nav_menu( 'footer' ) ){
					$copyright_link = get_field('copyright_link', 'option');
					$footer_copyright = '<li>&copy;2020';
					if($copyright_link) $footer_copyright .= ' <a href="'.$copyright_link['url'].'" target="_blank">'.$copyright_link['title'].'</a>';
					$footer_copyright .= '</li>';
					wp_nav_menu( array(
						'container' => 'nav',
						'container_class' => 'footer-nav',
						'theme_location' => 'footer',
						'menu_id'        => 'navigation-footer',
						'menu_class'     => '',
						'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s'.$footer_copyright.'</ul>',
						'walker'         => new Custom_Walker_Nav_Menu
						)
					);
				}
				$logo = get_field('logo_footer', 'option');
				if($logo){ ?>
				<div class="logo">
				  <a href="<?php echo home_url(); ?>">
					<img src="<?php echo $logo ?>" alt="<?php bloginfo( 'name' ); ?>">
				  </a>
				</div>
				<?php } ?>
			  </div>
			</footer>
		</div>
		<?php wp_footer(); ?>
	</body>
</html>
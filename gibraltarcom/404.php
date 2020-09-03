<?php get_header(); ?>
	<section class="error-block container">
        <div class="holder">
			<?php $code = http_response_code();
			if ($code !== NULL) {
				switch ($code) {
					case 100: $text = __('Continue', 'gibraltarcom' ); break;
					case 101: $text = __('Switching Protocols', 'gibraltarcom' ); break;
					case 200: $text = __('OK', 'gibraltarcom' ); break;
					case 201: $text = __('Created', 'gibraltarcom' ); break;
					case 202: $text = __('Accepted', 'gibraltarcom' ); break;
					case 203: $text = __('Non-Authoritative Information', 'gibraltarcom' ); break;
					case 204: $text = __('No Content', 'gibraltarcom' ); break;
					case 205: $text = __('Reset Content', 'gibraltarcom' ); break;
					case 206: $text = __('Partial Content', 'gibraltarcom' ); break;
					case 300: $text = __('Multiple Choices', 'gibraltarcom' ); break;
					case 301: $text = __('Moved Permanently', 'gibraltarcom' ); break;
					case 302: $text = __('Moved Temporarily', 'gibraltarcom' ); break;
					case 303: $text = __('See Other', 'gibraltarcom' ); break;
					case 304: $text = __('Not Modified', 'gibraltarcom' ); break;
					case 305: $text = __('Use Proxy', 'gibraltarcom' ); break;
					case 400: $text = __('Bad Request', 'gibraltarcom' ); break;
					case 401: $text = __('Unauthorized', 'gibraltarcom' ); break;
					case 402: $text = __('Payment Required', 'gibraltarcom' ); break;
					case 403: $text = __('Forbidden', 'gibraltarcom' ); break;
					case 404: $text = __('PAGE NOT FOUND', 'gibraltarcom' ); break;
					case 405: $text = __('Method Not Allowed', 'gibraltarcom' ); break;
					case 406: $text = __('Not Acceptable', 'gibraltarcom' ); break;
					case 407: $text = __('Proxy Authentication Required', 'gibraltarcom' ); break;
					case 408: $text = __('Request Time-out', 'gibraltarcom' ); break;
					case 409: $text = __('Conflict', 'gibraltarcom' ); break;
					case 410: $text = __('Gone', 'gibraltarcom' ); break;
					case 411: $text = __('Length Required', 'gibraltarcom' ); break;
					case 412: $text = __('Precondition Failed', 'gibraltarcom' ); break;
					case 413: $text = __('Request Entity Too Large', 'gibraltarcom' ); break;
					case 414: $text = __('Request-URI Too Large', 'gibraltarcom' ); break;
					case 415: $text = __('Unsupported Media Type', 'gibraltarcom' ); break;
					case 500: $text = __('SYSTEM ERROR', 'gibraltarcom' ); break;
					case 501: $text = __('Not Implemented', 'gibraltarcom' ); break;
					case 502: $text = __('Bad Gateway', 'gibraltarcom' ); break;
					case 503: $text = __('Service Unavailable', 'gibraltarcom' ); break;
					case 504: $text = __('Gateway Time-out', 'gibraltarcom' ); break;
					case 505: $text = __('HTTP Version not supported', 'gibraltarcom' ); break;
					default:
						$text = __('Unknown http status code', 'gibraltarcom') . '"' . htmlentities($code) . '"';
					break;
				}
			
			} ?>
			<h1><?php _e( 'Sorry for the inconvenience.', 'gibraltarcom' ); ?></h1>
			<?php if($code) echo '<strong class="subhead text-blue">'.__('Error',  'gibraltarcom').' '.$code.' - '.$text.'</strong>';?>
			<?php the_field($code.'_page_content', 'option') ?>
			<a href="<?php echo home_url(); ?>" class="btn-cta"><?php _e( 'Return To Homepage', 'gibraltarcom' ); ?></a>
		</div>
	</section>
	<?php if(have_rows('blue_block', 'option'))
	{ ?>
		<div class="modules-holder">
			<section class="info-block container bg-dark">
				<div class="row">
					<?php while(have_rows('blue_block', 'option'))
						{ the_row();
							$link = get_sub_field('link'); ?>
							<article class="info-col col">
								<div class="text-holder">
									<h3>
										<?php if($link){ ?><a href="<?php echo $link['url']; ?>"><?php } ?>
											<?php the_sub_field('title') ?>
										<?php if($link){ ?></a><?php } ?>
									</h3>
									<?php the_sub_field('text'); ?>
								</div>
								<?php if($link){
									$target = $link['target'] ? $link['target'] : '_self';
									$btn_title = $link['title'] ? $link['title'] : __('Learn more', 'gibraltarcom'); ?>
									<a href="<?php echo $link['url']; ?>" target="<?php echo $target ?>" class="btn-cta"><?php echo $btn_title; ?></a>
								<?php } ?>
							</article>
							<?php
						} ?>					
				</div>
			</section>
		</div>
	<?php
	} ?>
<?php get_footer(); ?>
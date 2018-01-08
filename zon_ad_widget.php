<?php
/**
 * @package ZEIT ONLINE Ad Widget
 *
 * Plugin Name:       ZEIT ONLINE Ad Widget
 * Plugin URI:        https://github.com/ZeitOnline/zon-ad-widget
 * Description:       Wordpress widget to display ZEIT ONLINE ads
 * Version:           0.1
 * Author:            Moritz Stoltenburg
 * Author URI:        http://slomo.de/
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * GitHub Plugin URI: https://github.com/ZeitOnline/zon-ad-widget
*/

/**
 * Adds ZonAdWidget widget.
 */
class ZonAdWidget extends WP_Widget {
	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'zon_ad_widget', // Base ID
			'ZON Ad 2.0', // Name
			array( 'description' => 'Cash rules everything around me' ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		echo $args['before_widget'];

		echo $this->_render_ad( $instance );

		echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		if ( isset( $instance['ad_channel'] ) ) {
			$ad_channel = $instance['ad_channel'];
		}
		else {
			$ad_channel = 'zeitonline/blogs';
		}

		if ( isset( $instance['ad_number'] ) ) {
			$ad_number = $instance['ad_number'];
		}
		else {
			$ad_number = 8;
		}

		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'ad_channel' ); ?>">Bannerkennung</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'ad_channel' ); ?>" name="<?php echo $this->get_field_name( 'ad_channel' ); ?>" type="text" value="<?php echo esc_attr( $ad_channel ); ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('ad_number'); ?>">Ad Tile</label>
			<select name="<?php echo $this->get_field_name('ad_number'); ?>" id="<?php echo $this->get_field_id('ad_number'); ?>">
				<option value="7" <?php selected( $ad_number, 7 ); ?>>7</option>
				<option value="8" <?php selected( $ad_number, 8 ); ?>>8</option>
				<option value="9" <?php selected( $ad_number, 9 ); ?>>9</option>
			</select>
		</p>
		<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array(
			'ad_number' => '',
			'ad_channel' => '',
		);

		foreach ( $instance as $key => $value ) {
			if ( ! empty( $new_instance[$key] ) ) {
				$instance[$key] = strip_tags( $new_instance[$key] );
			}
		}

		return $instance;
	}

	private function _render_ad( $instance ) {
		$channel = $instance['ad_channel'];
		$tile = $instance['ad_number'];
		$no = $tile - 6; // funky
		$ord = rand(100000000, 999999999);

		echo <<<HTML
			<div id="iqadtile{$tile}" class="mr ad place_{$tile}">
				<span class="anzeige">Anzeige</span>
				<div class="innerad">
					<div class="inner">
						<!-- Bannerplatz "info-mr-{$no}" Nr.{$tile} Tile:{$tile} -->
						<script type="text/javascript">
							document.write('<script src="http://ad.de.doubleclick.net/adj/{$channel};'
							+ 'tile={$tile};'
							+ n_pbt
							+ ';sz=300x250'
							+ ';kw=zeitonline,'+ iqd_TestKW
							+ ';ord=' + IQD_varPack.ord + '?" type="text/javascript"><\/script>');
						</script>
						<noscript>
							<div>
								<a href="http://ad.de.doubleclick.net/jump/{$channel};tile={$tile};sz=300x250;kw=zeitonline,;ord={$ord}?" rel="nofollow">
									<img src="http://ad.de.doubleclick.net/ad/{$channel};tile={$tile};sz=300x250;kw=zeitonline,;ord={$ord}?" width="300" height="250" alt="" />
								</a>
							</div>
						</noscript>
					</div>
				</div>
			</div>

HTML;

	}
}

// register ZonAdWidget widget
function register_zon_ad_widget() {
	register_widget( 'ZonAdWidget' );
}
add_action( 'widgets_init', 'register_zon_ad_widget' );

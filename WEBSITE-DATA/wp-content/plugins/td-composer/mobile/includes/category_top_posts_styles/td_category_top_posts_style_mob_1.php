<?php
class td_category_top_posts_style_mob_1 extends td_category_top_posts_style {
	// we keep the buffer and posts count for private use here:
	private $rendered_block_buffer = '';
	private $rendered_posts_count = 0;


	protected function render_posts_to_buffer() {

		// the global category top posts grid style
		$td_grid_style = 'td-grid-style-1';

		// the panel global posts limit for grids
		$tdm_grids_posts_limit = td_util::get_option('tdm_grids_posts_limit');
		$limit = ( !empty( $tdm_grids_posts_limit ) ? $tdm_grids_posts_limit : td_api_category_top_posts_style::get_key(get_class($this), 'posts_shown_in_the_loop') );

		// current block
		$block_name = td_api_category_top_posts_style::get_key(get_class($this), 'td_block_name');

		// check the filter
		$filter_by = '';
		if ( isset( $_GET['filter_by'] ) ) {
			$filter_by = $_GET['filter_by'];
		}

		// parameters to filter to for big grid
		$atts_for_big_grid = array(
			'limit' => $limit,
			'category_id' => td_global::$current_category_obj->cat_ID,
			'sort' => $filter_by,
			'td_grid_style' => $td_grid_style
		);

		//show the big grid
		$block_instance = td_global_blocks::get_instance($block_name);
		$this->rendered_block_buffer = $block_instance->render($atts_for_big_grid);
		$this->rendered_posts_count = $block_instance->td_query->post_count;

		if ($this->rendered_posts_count > 0) {
			td_global::$custom_no_posts_message = false;
		}
		// use class_name($this) to get the id :)
	}

	/**
	 * gets the buffer, should be called after @see render_posts_to_buffer();
	 * @return string
	 */
	protected function get_buffer() {
		return $this->rendered_block_buffer;
	}



	/**
	 * gets the number of posts @via td_global_blocks::get_instance($block_name)->td_query->post_count;
	 * @see render_posts_to_buffer();
	 * @return string
	 */
	protected function get_rendered_post_count() {
		return $this->rendered_posts_count;
	}



	function show_top_posts() {

		$this->render_posts_to_buffer();
		$tdm_category_grid = td_util::get_option('tdm_category_grid');

		// stop here if we don't have any posts to show in the grid of if the grid is disabled
		if ( $this->get_rendered_post_count() == 0 or $tdm_category_grid === 'hide' ) {
			return;
		}

		?>

		<!-- big grid -->
		<div class="td-category-grid">
			<div class="td-container">
				<div class="td-pb-row">
					<div class="td-pb-span12">
						<?php
						echo $this->get_buffer();
						?>
					</div>
				</div>
			</div>
		</div>
	<?php
	}
}
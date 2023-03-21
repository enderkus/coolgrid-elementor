<?php
/**
 * Elementor Cool Grid Widget.
 *
 * Elementor widget that inserts an embbedable content into the page, from any given URL.
 *
 * @since 1.0.0
 */
class Cool_Grid_Widget extends \Elementor\Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve Cool Grid widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'Cool Grid';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve Cool Grid widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Cool Grid', 'coolgrid-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve Cool Grid widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-posts-grid';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the Cool Grid widget belongs to.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'Cool Grid' ];
	}

	/**
	 * Register Cool Grid widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */

	protected function _register_controls() {

		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Settings', 'coolgrid-elementor' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'widget_title',
			[
				'label' => __( 'Widget Title', 'coolgrid-elementor' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'input_type' => 'text',
				'placeholder' => __( 'Latest News', 'coolgrid-elementor' ),
				'default' => 'Latest News',
			]
        );

		function cool_grid_post_type(){
			$args= array(
					'public'	=> 'true',
					'_builtin'	=> false
			);
			$post_types = get_post_types( $args, 'names', 'and' );
			$post_types = array( 'post'	=> 'post' ) + $post_types;
			return $post_types;
		}

		$this->add_control(
			'post_type',
			[
				'label' => __( 'Post Type', 'coolgrid-elementor' ),
				'type' => Elementor\Controls_Manager::SELECT,
				'options' => cool_grid_post_type(),
				'default' => 'post',  
			]
		);

		$this->add_control(
			'number_of_posts',
			[
				'label' => __( 'Number of posts', 'coolgrid-elementor' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'input_type' => 'number',
				'default' => '7',
			]
        );

		$this->end_controls_section();

	}

	/**
	 * Render Cool Grid widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {

		$settings = $this->get_settings_for_display();

		$q_args = array(
			'posts_per_page' =>  $settings['number_of_posts'],
			'post_type' => $settings['post_type'],
			'post_status' => 'publish',
		);

		$i = 1;
      	$q = new WP_Query($q_args);

		$output = '<header class="coolgrid-header">
        		<h1>'.$settings['widget_title'].'</h1>
      		  </header>
      	<div class="coolgrid-band">';

		  if( $q->have_posts() ) :
			while( $q->have_posts() ) : $q->the_post();
			  global $post;
	
			  $post_title = get_the_title();
			  $post_excerpt = get_the_excerpt();
			  $post_author = get_the_author();
			  $permalink = get_the_permalink();
	
			  $output .= '<div class="coolgrid-item-'.$i.'">
			  <a href="'.$permalink.'" class="coolgrid-card">';
			  if(has_post_thumbnail( $post->ID )) {
				$output .= '<div class="thumb" style="background-image: url('.get_the_post_thumbnail_url($post->ID).');"></div>';
			  }
				$output .='<article>
				  <h1>'.get_the_title().'</h1>';
				  if (!empty($post_excerpt)) {
					$output .= '<p>'.$post_excerpt.'</p>';
				  }
				  
			  $output .= '<span>'.get_the_author().'</span>
				</article>
			  </a>
			</div>';
			$i++;
	
			endwhile;
		  endif;

		  echo $output;

	}

}
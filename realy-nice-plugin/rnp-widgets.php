<?php

/*********
 * 
 * Widget with old_events calendar
 * 
 *********/

add_action('widgets_init', 'rnp_reg_calendar_widget');

function rnp_reg_calendar_widget() {
  register_widget('RNP_Caledar_Widget');
}
/**
 * Adds Calendar Widget
 */
class RNP_Caledar_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */

	function __construct() {
		parent::__construct(
			'rn_widget', 
			esc_html__( 'RNP Caledar Widget', 'text_domain' ), // Name
      array( 'description' => esc_html__( 'Realy nice widget with calendar. 
      You can press on the date and select events fro the date', 'text_domain' ), ) // Args
		);
	}

	public function widget( $args, $instance ) {
		echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
    } 
    
    ?>
      
			<div class="wrapper">
      <div class="container-calendar">
          <h3 id="monthAndYear"></h3>
          <div class="button-container-calendar">
              <button id="previous" onclick="previous()">&#8249;</button>
              <button id="next" onclick="next()">&#8250;</button>
          </div>
          
          <table class="table-calendar" id="calendar" data-lang="en">
              <thead id="thead-month"></thead>
              <tbody id="calendar-body"></tbody>
          </table>
          
          <div class="footer-container-calendar">
              <label for="month">Jump To: </label>
              <select id="month" onchange="jump()">
                  <option value=0>Jan</option>
                  <option value=1>Feb</option>
                  <option value=2>Mar</option>
                  <option value=3>Apr</option>
                  <option value=4>May</option>
                  <option value=5>Jun</option>
                  <option value=6>Jul</option>
                  <option value=7>Aug</option>
                  <option value=8>Sep</option>
                  <option value=9>Oct</option>
                  <option value=10>Nov</option>
                  <option value=11>Dec</option>
              </select>
              <select id="year" onchange="jump()"></select>       
          </div>
      </div>
    </div>

		<?php echo $args['after_widget'];
	}

	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'New title', 'text_domain' );
		?>
		<p>
		<label 
      for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'text_domain' ); ?>
    </label> 
		<input 
      class="widefat" 
      id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" 
      name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" 
      type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<?php 
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';

		return $instance;
	}

}


/*********
 * 
 * Widget with old_events search
 * 
 *********/

add_action('widgets_init', 'rnp_reg_search_widget');

function rnp_reg_search_widget() {
  register_widget('RNP_Search_Widget');
}
/**
 * Adds Calendar Widget
 */
class RNP_Search_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */

	function __construct() {
		parent::__construct(
			'rn_widget_s', 
			esc_html__( 'RNP Search Widget', 'text_domain' ), // Name
      array( 'description' => esc_html__( 'The widget has search string for search old events
      and important people', 'text_domain' ), ) // Args
		);
	}

	public function widget( $args, $instance ) {
		echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
    } 
    
    ?>
      
		<div class="search">
      <div class="search-wrapper">
          <!-- search icon -->
          <div class="seacrh-icon">
          <svg class="search-svg" width="40" height="40" viewBox="0 0 249 237" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M249 223.299L163.531 149.05C175.968 133.396 183.409 113.603 183.409 92.103C183.409 41.5371 142.27 0.39856 91.7045 0.39856C41.1385 0.39856 0 41.5371 0 92.103C0 142.669 41.1385 183.807 91.7045 183.807C114.43 183.807 135.248 175.495 151.286 161.755L237.443 236.601L249 223.299ZM91.7045 172.099C47.5944 172.099 11.7082 136.213 11.7082 92.103C11.7082 47.993 47.5944 12.1068 91.7045 12.1068C135.815 12.1068 171.701 47.993 171.701 92.103C171.701 136.213 135.815 172.099 91.7045 172.099Z" />
          </svg>
          </div>
          <!-- search input -->
          <input class="old-search" type="search" placeholder="Search" />
        </div>
      <div class="filters">
        <button class="open-filters-btn">Filters</button>
        <div class="filters-displayer">
          <div class="ranger">
            <label class="d-i-l" for="inday">Day</label>
            <div class="date-input">
              <div class="one-input">
                <input id="inday1min" type="number"/>
                  <input class="range-inputs" id="inday1" type="range" min="1" max="15" /><input class="range-inputs" id="inday2" type="range" min="16" max="31" />
                <input id="inday2max" type="number"/>
              </div>
            </div>

            <label class="d-i-l" for="inmonth">Month</label>
            <div class="date-input">
              <div class="one-input">
                <input id="inmonth1min" type="number"/>
                  <input class="range-inputs" id="inmonth1" type="range" min="1" max="6" /><input class="range-inputs" id="inmonth2" type="range" min="7" max="12" />  
                <input id="inmonth2max" type="number"/>
              </div>
            </div>

            <label class="d-i-l" for="inyear">Year</label>
            <div class="date-input">
              <div class="one-input">
                <input id="inyear1min" type="number"/>
                  <input class="range-inputs" id="inyear1" type="range" min="1990" max="2005" /><input class="range-inputs" id="inyear2" type="range" min="2006" max="2020" />
                  <input id="inyear2max" type="number"/>
              </div>
            </div>
          </div>
          <div class="importances">
            <span class="imp-head">Imp</span>
            <div class="one-importance">
              <label>1</label>
              <input class="importance" type="checkbox" />
            </div>
            <div class="one-importance">
              <label>2</label>
              <input class="importance" type="checkbox" />
            </div>
            <div class="one-importance">
              <label>3</label>
              <input class="importance" type="checkbox" />
            </div>
            <div class="one-importance">
              <label>4</label>
              <input class="importance" type="checkbox" />
            </div>
            <div class="one-importance">
              <label>5</label>
              <input class="importance" type="checkbox" />
            </div>
          </div>
        </div> 
      </div>
    </div>

		<?php echo $args['after_widget'];
	}

	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'New title', 'text_domain' );
		?>
		<p>
		<label 
      for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'text_domain' ); ?>
    </label> 
		<input 
      class="widefat" 
      id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" 
      name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" 
      type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<?php 
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';

		return $instance;
	}

} 
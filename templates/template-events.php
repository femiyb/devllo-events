<?php

class Devllo_Events_Template_Display {
    public function __construct(){   
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
        add_shortcode('devllo-events', array($this, 'display_calendar'));


    }

    function enqueue_scripts() {   

      wp_register_script( 'fullcalendar_js', DEVLLO_EVENTS_INC_URI. 'assets/js/main.js' );
      wp_enqueue_script( 'fullcalendar_js');

      wp_register_script( 'fullcalendar_min_js', DEVLLO_EVENTS_INC_URI. 'assets/js/main.min.js' );
      wp_enqueue_script( 'fullcalendar_min_js');  

      wp_enqueue_style( 'calendar_css', DEVLLO_EVENTS_INC_URI. 'assets/css/main.css');	

      wp_enqueue_style( 'font_css', 'https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.13.1/css/all.css');	

      wp_enqueue_style('full_calendar_bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.css');


      wp_register_script( 'jquery_min_js_online', 'https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js' );
      wp_enqueue_script( 'jquery_min_js_online');

     wp_register_script( 'jquery_min_js', DEVLLO_EVENTS_INC_URI. 'assets/js/jquery.min.js' );
      wp_enqueue_script( 'jquery_min_js');
    }

    function display_calendar($content = ""){
      if (!is_admin()){
      global $post;
      $args = array( 
           'post_type' => 'devllo_event', 
           'post_status' => 'publish', 
           'nopaging' => true 
       ); 
     
     $posts = get_posts( $args );
     
       $output = array();
      foreach( $posts as $post ) {  
        $startdate = get_post_meta( $post->ID, '_start_year', true ). '-' .get_post_meta( $post->ID, '_start_month', true ). '-' .get_post_meta( $post->ID, '_start_day', true ). 'T' .get_post_meta( $post->ID, '_start_hour', true ). ':' .get_post_meta( $post->ID, '_start_minute', true );
        $enddate = get_post_meta( $post->ID, '_end_year', true ). '-' .get_post_meta( $post->ID, '_end_month', true ). '-' .get_post_meta( $post->ID, '_end_day', true ). 'T' .get_post_meta( $post->ID, '_end_hour', true ). ':' .get_post_meta( $post->ID, '_end_minute', true );
        $url = get_permalink( $post->ID ); 
           // Pluck the id and title attributes
       $output[] = array( 'id' => $post->ID, 'title' => $post->post_title, 'start' => $startdate, 'end' => $enddate, 'url' => $url );
       
     } 

 ?>
    <script>
document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
          events: <?php echo json_encode( $output );?>,
          headerToolbar: {
       // center: 'title',
      },
      navLinks: true, // can click day/week names to navigate views
      dayMaxEvents: true, // allow "more" link when too many events


          initialView: 'listYear'
        });
        calendar.render();
      });

</script>

<?php 
return "<div id='calendar'></div>" . $content;
}
  }
}
new Devllo_Events_Template_Display();
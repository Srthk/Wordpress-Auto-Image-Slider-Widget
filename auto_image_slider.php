<?php
/*
Plugin Name: SRTK Auto Image Slider Widget
Plugin URI: http://github.com/srthk
Description: A widget plugin to display images as slider which are hyperlinked.
Version: 1.0
Author: Sarthak Singhal
Author URI: http://github.com/srthk
Text Domain: autoimageslidertextdomain
License: GPLv2
 
Copyright 2016 Sarthak
 
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.
 
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
 
 
class AutoImageSlider_Widget extends WP_Widget {
 
    public function __construct() {
     
        parent::__construct(
            'autoimageslider_widget',
            __( 'SRTK Auto Image Slider Widget', 'autoimageslidertextdomain' ),
            array(
                'classname'   => 'autoimageslider_widget',
                'description' => __( 'A widget plugin to display images as slider which are hyperlinked.', 'autoimageslidertextdomain' )
                )
        );
       
        load_plugin_textdomain( 'autoimageslidertextdomain', false, basename( dirname( __FILE__ ) ) . '/languages' );
       
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
         
      extract( $args );   
      $title = apply_filters( 'widget_title', $instance['title'] );
      $count = $instance['count'];
      echo $before_widget;
      
      if ( $title ) {
            echo $before_title . $title . $after_title;
      }

      $links = $images = array();

      for($i=1; $i <= $count; $i++) {
          $lindex = "link_" . $i;
          $iindex = "image_". $i;
          array_push($links, $instance[$lindex]);
          array_push($images, $instance[$iindex]);
      }
        
      $links_string = implode(",", $links);
      $images_string = implode(",", $images);

?>

      <a id="bannerLink" href="<?php echo $instance['link_1'];?>" onclick="void window.open(this.href); return false;">
        <img id="bannerImage" src="<?php echo $instance['image_1'];?>">
      </a>
      <li class='dots'></li>
        
<?php
      echo "<div id='slider_links' style='display:none;'>" . $links_string . "</div>";
      echo "<div id='slider_images' style='display:none;'>" . $images_string . "</div>";
      echo $after_widget;         
    } //End of function widget
 
  
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
        $instance = $old_instance;
        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance['count'] = strip_tags( $new_instance['count'] );
        $count =  strip_tags( $new_instance['count'] );
        for($i=1; $i <= $count; $i++) {
           $lindex = "link_" . $i;
           $iindex = "image_". $i;
           $instance[$lindex]    = strip_tags( $new_instance[$lindex] );
           $instance[$iindex]     = strip_tags( $new_instance[$iindex] );
        }
        return $instance;    
    }
  
    /**
      * Back-end widget form.
      *
      * @see WP_Widget::form()
      *
      * @param array $instance Previously saved values from database.
      */
    public function form( $instance ) {    
     
        $title      = esc_attr( $instance['title'] );
        $count      = esc_attr( $instance['count'] );
       
        if($count == "") {
          $count = 2;
        }
  
        for($i=1; $i <= $count; $i++) {
          $lindex = "link_" . $i;
          $iindex = "image_". $i; 
          ${"link_" . $i}       = esc_attr( $instance[$lindex] );
          ${"image_" . $i}     = esc_attr( $instance[$iindex] ); 
        }

        if($title == "") {
          $title = "Featured";
        }

?>
         
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('count'); ?>"><?php _e('Count:'); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" type="number" value="<?php echo $count; ?>" />
        </p>

<?php
        for($i=1; $i <= $count; $i++) {
?>
       
        <p>
            <label for="<?php echo $this->get_field_id('link_' . $i); ?>"><?php _e('Link ' . $i . ':'); ?></label> 
            <textarea class="widefat" rows="2" cols="20" id="<?php echo $this->get_field_id('link_' . $i); ?>" name="<?php echo $this->get_field_name('link_' . $i); ?>"><?php echo ${"link_" . $i}; ?></textarea>
        </p>
        <p>   
            <label for="<?php echo $this->get_field_id('image_' . $i); ?>"><?php _e('Image' . $i . ':'); ?></label> 
            <textarea class="widefat" rows="3" cols="20" id="<?php echo $this->get_field_id('image_' . $i); ?>" name="<?php echo $this->get_field_name('image_' . $i); ?>"><?php echo ${"image_" . $i}; ?></textarea>
        </p>
     
<?php 
       }
    }
     
}
 
/* Register the widget */
add_action( 'widgets_init', function(){
     register_widget( 'AutoImageSlider_Widget' );
});

add_action('wp_footer', function(){
    wp_register_script( 'slider_featured', plugins_url('slider_featured.js',__FILE__ ));
    wp_enqueue_script('slider_featured');
});

add_action('wp_head', function(){
    wp_register_style('slider', plugins_url('style.css',__FILE__ ));
    wp_enqueue_style('slider');
});

?>
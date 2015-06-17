<?php
add_action( 'admin_menu', 'scrollup_add_admin_menu' );
add_action( 'admin_init', 'scrollup_settings_init' );


function scrollup_add_admin_menu(  ) { 

	add_options_page( 'Smooth Scroll Up', 'Smooth Scroll Up', 'publish_pages', 'smooth_scroll_up', 'scroll_up_options_page' );

}


function scrollup_settings_exist(  ) { 

	if( false == get_option( 'scroll_up_settings' ) ) { 

		add_option( 'scroll_up_settings' );

	}

}


function scrollup_settings_init(  ) { 

	register_setting( 'scrollup_options_page', 'scrollup_settings' );
	
	add_settings_section(
		'scrollup_options_section', 
		__( 'Options', 'scrollup' ), 
		'scrollup_options_section_callback', 
		'scrollup_options_page'
	);
	
	add_settings_field( 
		'scrollup_text', 
		__( 'Text', 'scrollup' ), 
		'scrollup_text_render', 
		'scrollup_options_page', 
		'scrollup_options_section' 
	);

	add_settings_field( 
		'scrollup_type', 
		__( 'Type', 'scrollup' ), 
		'scrollup_type_render', 
		'scrollup_options_page', 
		'scrollup_options_section' 
	);

	add_settings_field( 
		'scrollup_position', 
		__( 'Position', 'scrollup' ), 
		'scrollup_position_render', 
		'scrollup_options_page', 
		'scrollup_options_section' 
	);

	add_settings_field( 
		'scrollup_show', 
		__( 'Show in homepage', 'scrollup' ), 
		'scrollup_show_render', 
		'scrollup_options_page', 
		'scrollup_options_section' 
	);

	add_settings_field( 
		'scrollup_mobile', 
		__( 'Show in mobile devices', 'scrollup' ), 
		'scrollup_mobile_render', 
		'scrollup_options_page', 
		'scrollup_options_section' 
	);
	
	add_settings_field( 
		'scrollup_animation', 
		__( 'Show animation', 'scrollup' ), 
		'scrollup_animation_render', 
		'scrollup_options_page', 
		'scrollup_options_section' 
	);

	add_settings_field( 
		'scrollup_distance', 
		__( 'Distance from top before showing scroll up element', 'scrollup' ), 
		'scrollup_distance_render', 
		'scrollup_options_page', 
		'scrollup_options_section' 
	);

	add_settings_field( 
		'scrollup_attr', 
		__( 'Onclick event', 'scrollup' ), 
		'scrollup_attr_render', 
		'scrollup_options_page', 
		'scrollup_options_section' 
	);

}


function scrollup_text_render(  ) { 

	$options = get_option( 'scrollup_settings' );
	?>
	<input type='text' name='scrollup_settings[scrollup_text]' value='<?php echo $options['scrollup_text']; ?>'>
	<?php

}


function scrollup_type_render(  ) { 

	$options = get_option( 'scrollup_settings' );
	?>
	<select name='scrollup_settings[scrollup_type]'>
		<option value='image' <?php selected( $options['scrollup_type'], 'image' ); ?>><?php _e('Image', 'scrollup'); ?></option>
		<option value='link' <?php selected( $options['scrollup_type'], 'link' ); ?>><?php _e('Text link', 'scrollup'); ?></option>
		<option value='pill' <?php selected( $options['scrollup_type'], 'pill' ); ?>><?php _e('Pill', 'scrollup'); ?></option>
		<option value='tab' <?php selected( $options['scrollup_type'], 'tab' ); ?>><?php _e('Tab', 'scrollup'); ?></option>
	</select>

<?php

}


function scrollup_position_render(  ) { 

	$options = get_option( 'scrollup_settings' );
	?>
	<select name='scrollup_settings[scrollup_position]'>
		<option value='left' <?php selected( $options['scrollup_position'], 'left' ); ?>><?php _e('Left', 'scrollup'); ?></option>
		<option value='right' <?php selected( $options['scrollup_position'], 'right' ); ?>><?php _e('Right', 'scrollup'); ?></option>
		<option value='center' <?php selected( $options['scrollup_position'], 'center' ); ?>><?php _e('Center', 'scrollup'); ?></option>
	</select>

<?php

}


function scrollup_show_render(  ) { 

	$options = get_option( 'scrollup_settings' );
	?>
	<select name='scrollup_settings[scrollup_show]'>
		<option value='0' <?php selected( $options['scrollup_show'], '0' ); ?>><?php _e('No', 'scrollup'); ?></option>
		<option value='1' <?php selected( $options['scrollup_show'], '1' ); ?>><?php _e('Yes', 'scrollup'); ?></option>
	</select>

<?php

}


function scrollup_mobile_render(  ) { 

	$options = get_option( 'scrollup_settings' );
	?>
	<select name='scrollup_settings[scrollup_mobile]'>
		<option value='0' <?php selected( $options['scrollup_mobile'], '0' ); ?>><?php _e('No', 'scrollup'); ?></option>
		<option value='1' <?php selected( $options['scrollup_mobile'], '1' ); ?>><?php _e('Yes', 'scrollup'); ?></option>
	</select>

<?php

}


function scrollup_distance_render(  ) { 

	$options = get_option( 'scrollup_settings' );
	?>
	<input type='text' name='scrollup_settings[scrollup_distance]' value='<?php echo $options['scrollup_distance']; ?>'>
	<span style="font-size:11px;font-style:italic;">px</span>
	<?php

}


function scrollup_animation_render(  ) { 

	$options = get_option( 'scrollup_settings' );
	?>
	<select name='scrollup_settings[scrollup_animation]'>
		<option value='none' <?php selected( $options['scrollup_animation'], 'none' ); ?>><?php _e('None', 'scrollup'); ?></option>
		<option value='fade' <?php selected( $options['scrollup_animation'], 'fade' ); ?>><?php _e('Fade', 'scrollup'); ?></option>
		<option value='slide' <?php selected( $options['scrollup_animation'], 'slide' ); ?>><?php _e('Slide', 'scrollup'); ?></option>
	</select>

<?php

}


function scrollup_attr_render(  ) { 

	$options = get_option( 'scrollup_settings' );
	?>
	<input type='text' name='scrollup_settings[scrollup_attr]' value='<?php echo $options['scrollup_attr']; ?>'>
	<?php
	echo '<span style="font-size:11px;font-style:italic;">';
	echo sprintf( __('example: type %s in order to add an event %s' , 'scrollup') , '<code>exit()</code>', '<code>exit()</code>' );
	echo '</span>';
}


function scrollup_options_section_callback(  ) { 

	echo __( 'This section contains options for setting up Smooth Scroll Up plugin', 'scrollup' );

}


function scroll_up_options_page(  ) { 

	?>
	<form action='options.php' method='post'>
		
		<h2><?php echo __( 'Smooth Scroll Up', 'scrollup' ); ?></h2>
		
		<?php
		settings_fields( 'scrollup_options_page' );
		do_settings_sections( 'scrollup_options_page' );
		submit_button();
		?>
		
	</form>
	<?php

}

?>
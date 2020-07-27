<?php
/**
* Plugin Name: Tone Cookie
* Plugin URI: https://www.tierone.pt/
* Description: Yeat another cookie banner notice, but creates it per page basis. It gives you total control.
* Version: 1.0
* Author: Pedro Dias @ TierOne
* Author URI: https://www.tierone.pt/
**/

//settings

function t1cookie_add_settings_page() {
    add_options_page( 'Tone Cookie', 'Tone Cookie', 'manage_options', "tone-cookie", 't1cookie_render_plugin_settings_page' );
}
add_action( 'admin_menu', 't1cookie_add_settings_page' );

function t1cookie_render_plugin_settings_page() {
    ?>
    <h2>Settings</h2>
    <form action="options.php" method="post">
        <?php
        settings_fields( 'tone_cookie_options' );
        do_settings_sections( 'tone_cookie' ); ?>
        <input name="submit" class="button button-primary" type="submit" value="<?php esc_attr_e( 'Save' ); ?>" />
    </form>
    <?php
}

function t1cookie_register_settings() {
    register_setting( 'tone_cookie_options', 'tone_cookie_options', 'tone_cookie_options_validate' );
    add_settings_section( 'api_settings', 'API Settings', 't1cookie_section_text', 'tone_cookie' );

    add_settings_field( 't1cookie_setting_from0', 'From', 't1cookie_setting_from0', 'tone_cookie', 'api_settings' );
    add_settings_field( 't1cookie_setting_to0', 'To', 't1cookie_setting_to0', 'tone_cookie', 'api_settings' );
    add_settings_field( 't1cookie_setting_text0', 'Texto', 't1cookie_setting_text0', 'tone_cookie', 'api_settings' );

    add_settings_field( 't1cookie_setting_from1', 'From', 't1cookie_setting_from1', 'tone_cookie', 'api_settings' );
    add_settings_field( 't1cookie_setting_to1', 'To', 't1cookie_setting_to1', 'tone_cookie', 'api_settings' );
    add_settings_field( 't1cookie_setting_text1', 'Texto', 't1cookie_setting_text1', 'tone_cookie', 'api_settings' );

}
add_action( 'admin_init', 't1cookie_register_settings' );


function tone_cookie_options_validate( $input ) {
    $newinput['text'] = trim( $input['text'] );
    if ( ! preg_match( '/^[a-z0-9]{32}$/i', $newinput['text'] ) ) {
        $newinput['text'] = '';
    }

    return $input;
}

function t1cookie_section_text() {
    echo '<p>Escolha Página a conter o popup, a página que abre em popup com os detalhes, e o texto a apresentar no balão com o ok </p>';
}

function t1cookie_setting_from0() {
    $options = get_option( 'tone_cookie_options' );
    echo "<input id='t1cookie_setting_from0' name='tone_cookie_options[from0]' type='text' value='$options[from0]' />";
}

function t1cookie_setting_to0() {
    $options = get_option( 'tone_cookie_options' );
    echo "<input id='t1cookie_setting_to0' name='tone_cookie_options[to0]' type='text' value='$options[to0]' />";
}

function t1cookie_setting_text0() {
    $options = get_option( 'tone_cookie_options' );
    echo "<input id='t1cookie_setting_text0' name='tone_cookie_options[text0]' type='text' value='$options[text0]' />";
}

function t1cookie_setting_from1() {
    $options = get_option( 'tone_cookie_options' );
    echo "<input id='t1cookie_setting_from1' name='tone_cookie_options[from1]' type='text' value='$options[from1]' />";
}
 
function t1cookie_setting_to1() {
    $options = get_option( 'tone_cookie_options' );
    echo "<input id='t1cookie_setting_to1' name='tone_cookie_options[to1]' type='text' value='$options[to1]' />";
}
 
function t1cookie_setting_text1() {
    $options = get_option( 'tone_cookie_options' );
    echo "<input id='t1cookie_setting_text1' name='tone_cookie_options[text1]' type='text' value='$options[text1]' />";
}


// webpage footer

function tone_cookie_footer_view() {
    global $post;
    $page_slug = $post->post_name;
    $options = get_option( 'tone_cookie_options' );
    $myCookie = isset( $_COOKIE['consent'] ) ? $_COOKIE['consent'] : 'Not Set!!';
    if($myCookie != "true"){
        $consent_warning_notice = "Este site utiliza cookies para permitir uma melhor experiência por parte do utilizador. Ao navegar no site estará a consentir a sua utilização.";
	if($page_slug == $options["from0"]){
	  $consent_warning_notice = $options["text0"];
	} else if($page_slug == $options["from1"]){
          $consent_warning_notice = $options["text1"];
        }
    ?>
    <script>
      function setCookie(cname, cvalue, exdays) {
        var d = new Date();
        d.setTime(d.getTime() + (exdays*24*60*60*1000));
        var expires = "expires="+ d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
      }
      function getCookie(cname) {
 	var name = cname + "=";
  	var decodedCookie = decodeURIComponent(document.cookie);
  	var ca = decodedCookie.split(';');
  	for(var i = 0; i <ca.length; i++) {
    	  var c = ca[i];
    	  while (c.charAt(0) == ' ') {
      	    c = c.substring(1);
    	  }
    	  if (c.indexOf(name) == 0) {
      	    return c.substring(name.length, c.length);
    	  }
        }
  	return "";
      }
      jQuery(document).ready(function( $ ) {
        $('#cookie-notice a[data-cookie-set="accept"]').click(function(event) {
          var barra_cookie_consent = $('#cookie-notice');
          barra_cookie_consent.hide();
          setCookie("consent","true",365);
          event.preventDefault();
      	})
      });
    </script>
    <div id="cookie-notice" role="banner" class="cookie-revoke-hidden cn-position-bottom cn-effect-fade cookie-notice-visible" aria-label="Cookie Notice" style="background-color: rgba(0,0,0,1);">
      <div class="cookie-notice-container" style="color: #fff;">
	<span id="cn-notice-text" class="cn-text-container"><?php echo $consent_warning_notice ?></span>
	  <span id="cn-notice-buttons" class="cn-buttons-container">
            <a href="#" id="cn-accept-cookie" data-cookie-set="accept" class="cn-set-cookie cn-button bootstrap" aria-label="Ok">Ok</a>
          </span>
	<a href="javascript:void(0);" id="cn-close-notice" data-cookie-set="accept" class="cn-close-icon" aria-label="Ok"></a>
	</div>
      </div>
    <?php
  }
}
add_action('wp_footer', 'tone_cookie_footer_view');

function tone_cookie_load_plugin_css() {
    $plugin_url = plugin_dir_url( __FILE__ );

    wp_enqueue_style( 'tone_cookie_style', $plugin_url . 'css/styles.css' );
}
add_action( 'wp_enqueue_scripts', 'tone_cookie_load_plugin_css' );

?>

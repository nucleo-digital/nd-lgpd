<?php

class ND_LGPD_Shortcode_Download_User_Data
{
	public $_instance = null;

	public function get_instance()
	{
		$this->_instance = empty($this->_instance) ? new ND_LGPD_Shortcode_Download_User_Data() : $this->_instance;

		$this->init_shortcode();

		return $this->_instance;
	}

	public function init_shortcode()
	{
		add_shortcode('nd_lgpd_download_user_data', array($this, 'nd_lgpd_download_user_data_callback'));
	}

	public function nd_lgpd_download_user_data_callback($atts = '')
	{
		$atts = shortcode_atts(array(
			'text' => __('Download de Dados', ND_LGPD_TEXT_DOMAIN),
			'msg' => __('Ao clicar em OK, você realizará o download dos dados do seu usuário, que estão contidos no nosso sistema.', ND_LGPD_TEXT_DOMAIN),
		), $atts, 'nd_lgpd_download_user_data');

		$this->enqueue($atts['text'], $atts['msg']);

		return $this->shortcode_template($atts);
	}

	public function enqueue($text, $msg)
	{
		wp_enqueue_script(ND_LGPD_PREFIX . 'script', ND_LGPD_PLUGIN_ASSETS . 'js/script.js', array('jquery'));
		wp_enqueue_script(ND_LGPD_PREFIX . 'xlsx', ND_LGPD_PLUGIN_ASSETS . 'js/xlsx/xlsx.full.min.js', array('jquery'));
		wp_enqueue_script(ND_LGPD_PREFIX . 'xlsx', ND_LGPD_PLUGIN_ASSETS . 'js/xlsx/xlsx.zahl.js', array('jquery'));

		$localize = array(
			'ajaxurl' => admin_url('admin-ajax.php'),
			'security' => wp_create_nonce('file_upload'),
			'nd_lgdp_prefix' 	=> ND_LGPD_PREFIX,
			'text_download' => $text,
		);
		wp_localize_script(ND_LGPD_PREFIX . 'script', 'nd_lgpd_options_object', $localize);
	}

	public function shortcode_template($atts = '')
	{
		ob_start();
?>
		<a href="" class="nd-lgpd-download-user-data" data-user-id="<?php echo get_current_user_id() ?>" data-msg="<?php echo $atts['msg'] ?>">
			<?php echo $atts['text'] ?>
		</a>
<?php

		return ob_get_clean();
	}
}

if (class_exists('ND_LGPD_Shortcode_Download_User_Data')) {
	$class = new ND_LGPD_Shortcode_Download_User_Data;
	$class->get_instance();
}

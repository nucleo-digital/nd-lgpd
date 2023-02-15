<?php

class ND_LGPD_Shortcode_Delete_User
{
	public $_instance = null;

	public function get_instance()
	{
		$this->_instance = empty($this->_instance) ? new ND_LGPD_Shortcode_Delete_User() : $this->_instance;

		$this->init_shortcode();

		return $this->_instance;
	}

	public function init_shortcode()
	{
		add_shortcode('nd_lgpd_delete_user', array($this, 'nd_lgpd_delete_user_callback'));
	}

	public function nd_lgpd_delete_user_callback($atts = '')
	{
		$atts = shortcode_atts(array(
			'text' => __('Apagar Usuário', ND_LGPD_TEXT_DOMAIN),
			'msg' => __('Tem certeza que desaja apagar todos seus dados? Isso irá excluir o seu usuário e todos os seus dados.', ND_LGPD_TEXT_DOMAIN),
		), $atts, 'nd_lgpd_delete_user');

		$this->enqueue($atts['text'], $atts['msg']);

		return $this->shortcode_template($atts);
	}

	public function enqueue($text, $msg)
	{
		wp_enqueue_script(ND_LGPD_PREFIX . 'script', ND_LGPD_PLUGIN_ASSETS . 'js/script.js', array('jquery'));
		$localize = array(
			'ajaxurl' => admin_url('admin-ajax.php'),
			'security' => wp_create_nonce('file_upload'),
			'nd_lgdp_prefix' 	=> ND_LGPD_PREFIX,
			'text_delete' => $text,
		);
		wp_localize_script(ND_LGPD_PREFIX . 'script', 'nd_lgpd_options_object', $localize);
	}

	public function shortcode_template($atts = '')
	{
		ob_start();
?>
		<a href="" class="nd-lgpd-delete-user-data" data-user-id="<?php echo get_current_user_id() ?>" data-msg="<?php echo $atts['msg'] ?>">
			<?php echo $atts['text'] ?>
		</a>
<?php

		return ob_get_clean();
	}
}

if (class_exists('ND_LGPD_Shortcode_Delete_User')) {
	$class = new ND_LGPD_Shortcode_Delete_User;
	$class->get_instance();
}

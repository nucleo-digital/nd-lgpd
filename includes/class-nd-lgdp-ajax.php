<?php

class ND_LGDP_Ajax
{
	public $_instance = null;
	public $ajax = array(
		'delete_user',
		'download_user_data',
	);

	public function get_instance()
	{
		$this->_instance = empty($this->_instance) ? new ND_LGDP_Ajax() : $this->_instance;

		$this->init_ajax();

		return $this->_instance;
	}

	public function init_ajax()
	{
		if (!$this->ajax) return false;

		foreach ($this->ajax as $value) {
			add_action('wp_ajax_' . ND_LGPD_PREFIX . $value, array($this, ND_LGPD_PREFIX . $value));
			add_action('wp_ajax_nopriv_' . ND_LGPD_PREFIX . $value, array($this, ND_LGPD_PREFIX . $value));
		}
	}

	public function nd_lgpd_delete_user()
	{
		$user_id = get_current_user_id();

		$user = get_user_by('ID', $user_id);

		$response = wp_delete_user($user_id);

		if ($response == 1) {
			$status = 'success';
			$msg = __("Usuário '{$user->data->display_name}' apagado com sucesso");
		} else {
			$status = 'error';
			$msg = __('Erro ao apagar usuário.', ND_LGPD_TEXT_DOMAIN);
		}

		wp_send_json(
			array(
				'status' => $status,
				'msg' => $msg,
			)
		);
		exit;
	}

	public function nd_lgpd_download_user_data()
	{
		$user_id = get_current_user_id();
		$user = get_user_by('ID', $user_id);
		$user_meta = get_user_meta($user_id);

		$data = array(
			'e-mail' => $user->data->user_email,
			'login' => $user->data->user_login,
			'nome' => $user_meta['first_name'][0],
			'sobrenome' => $user_meta['last_name'][0],
			'nickname' => $user_meta['nickname'][0],
		);

		// Faturamento
		if ($user_meta['billing_address_1'][0]) {
			$data['endereco[faturamento][endereco'] = $user_meta['billing_address_1'][0];
		}
		if ($user_meta['billing_address_2'][0]) {
			$data['endereco[faturamento][complemento]'] = $user_meta['billing_address_2'][0];
		}
		if ($user_meta['billing_city'][0]) {
			$data['endereco[faturamento][cidade]'] = $user_meta['billing_address_1'][0];
		}
		if ($user_meta['billing_country'][0]) {
			$data['endereco[faturamento][pais]'] = $user_meta['billing_country'][0];
		}
		if ($user_meta['billing_email'][0]) {
			$data['endereco[faturamento][email]'] = $user_meta['billing_email'][0];
		}
		if ($user_meta['billing_first_name'][0]) {
			$data['endereco[faturamento][nome]'] = $user_meta['billing_first_name'][0];
		}
		if ($user_meta['billing_last_name'][0]) {
			$data['endereco[faturamento][sobrenome]'] = $user_meta['billing_last_name'][0];
		}
		if ($user_meta['billing_phone'][0]) {
			$data['endereco[faturamento][telefone]'] = $user_meta['billing_phone'][0];
		}
		if ($user_meta['billing_postcode'][0]) {
			$data['endereco[faturamento][cep]'] = $user_meta['billing_postcode'][0];
		}
		if ($user_meta['billing_state'][0]) {
			$data['endereco[faturamento][estado]'] = $user_meta['billing_state'][0];
		}

		// // Entrega
		if ($user_meta['shipping_address_1'][0]) {
			$data['endereco[entrega][endereco'] = $user_meta['shipping_address_1'][0];
		}
		if ($user_meta['shipping_address_2'][0]) {
			$data['endereco[entrega][complemento]'] = $user_meta['shipping_address_2'][0];
		}
		if ($user_meta['shipping_city'][0]) {
			$data['endereco[entrega][cidade]'] = $user_meta['shipping_city'][0];
		}
		if ($user_meta['shipping_company'][0]) {
			$data['endereco[entrega][empresa]'] = $user_meta['shipping_company'][0];
		}
		if ($user_meta['shipping_country'][0]) {
			$data['endereco[entrega][pais]'] = $user_meta['shipping_country'][0];
		}
		if ($user_meta['shipping_first_name'][0]) {
			$data['endereco[entrega][nome]'] = $user_meta['shipping_first_name'][0];
		}
		if ($user_meta['shipping_last_name'][0]) {
			$data['endereco[entrega][sobrenome]'] = $user_meta['shipping_last_name'][0];
		}
		if ($user_meta['shipping_phone'][0]) {
			$data['endereco[entrega][telefone]'] = $user_meta['shipping_phone'][0];
		}
		if ($user_meta['shipping_postcode'][0]) {
			$data['endereco[entrega][cep]'] = $user_meta['shipping_postcode'][0];
		}
		if ($user_meta['shipping_state'][0]) {
			$data['endereco[entrega][estado]'] = $user_meta['shipping_state'][0];
		}

		// Social
		if ($user_meta['facebook'][0]) {
			$data['social[facebok]'] = $user_meta['facebook'][0];
		}
		if ($user_meta['instagram'][0]) {
			$data['social[instagram]'] = $user_meta['instagram'][0];
		}
		if ($user_meta['linkedin'][0]) {
			$data['social[linkedin]'] = $user_meta['linkedin'][0];
		}
		if ($user_meta['tumblr'][0]) {
			$data['social[tumblr]'] = $user_meta['tumblr'][0];
		}
		if ($user_meta['twitter'][0]) {
			$data['social[twitter]'] = $user_meta['twitter'][0];
		}
		if ($user_meta['wikipedia'][0]) {
			$data['social[wikipedia]'] = $user_meta['wikipedia'][0];
		}

		// Address CTH
		if (is_plugin_active('nd-arbo/nd-arbo.php')) {
			if ($user_meta['email'][0]) {
				$data['email_contato'] = $user_meta['email'][0];
			}
			if ($user_meta['phone'][0]) {
				$data['telefone'] = $user_meta['phone'][0];
			}
			if ($user_meta['user_url'][0]) {
				$data['website'] = $user_meta['user_url'][0];
			}
			if ($user_meta['company'][0]) {
				$data['empresa'] = $user_meta['company'][0];
			}
			if ($user_meta['country'][0]) {
				$data['pais'] = $user_meta['country'][0];
			}
			if ($user_meta['zip_code'][0]) {
				$data['cep'] = $user_meta['zip_code'][0];
			}
			if ($user_meta['address'][0]) {
				$data['logradouro'] = $user_meta['address'][0];
			}
			if ($user_meta['number'][0]) {
				$data['numero'] = $user_meta['number'][0];
			}
			if ($user_meta['neighborhood'][0]) {
				$data['bairro'] = $user_meta['neighborhood'][0];
			}
			if ($user_meta['address_2'][0]) {
				$data['complemento'] = $user_meta['address_2'][0];
			}
			if ($user_meta['city'][0]) {
				$data['cidade'] = $user_meta['city'][0];
			}
			if ($user_meta['state'][0]) {
				$data['estado'] = $user_meta['state'][0];
			}
			if ($user_meta['professional_activity'][0]) {
				$data['area_de_atuacao_profissional'] = implode(', ', maybe_unserialize($user_meta['professional_activity'][0]));
			}
			if ($user_meta['urbanist_architect'][0]) {
				$data['arquiteto_ou_urbanista'] = $user_meta['urbanist_architect'][0] == 1 ? 'Sim' : 'Não';
			}
			if ($user_meta['acting_in_architecture_and_urbanism'][0]) {
				$data['atuacao_arquitetura_e_urbanismos'] = implode(', ',  maybe_unserialize($user_meta['acting_in_architecture_and_urbanism'][0]));
			}

			if ($user_meta['cor_raca'][0]) {
				$data['cor_ou_raca'] = $user_meta['cor_raca'][0];
			}
			if ($user_meta['gender_identity'][0]) {
				$data['identidade_de_genero'] = $user_meta['gender_identity'][0];
			}
			if ($user_meta['sexual_orientation'][0]) {
				$data['orientacao_sexual'] = $user_meta['sexual_orientation'][0];
			}
		}


		wp_send_json(
			array(
				'status' => 'success',
				'user_id' => $user_id,
				'user' => $user,
				'user_meta' => $user_meta,
				'data' => array($data),
			)
		);
		exit;
	}
}

if (class_exists('ND_LGDP_Ajax')) {
	$class = new ND_LGDP_Ajax;
	$class->get_instance();
}

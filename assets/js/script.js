(function ($) {
	const ndLgdpAjax = async (data, success = () => { }, beforeSend = () => { }, complete = () => { }, dataType = 'json', typeAjax = 'POST') => {
		await $.ajax({
			type: typeAjax,
			url: nd_lgpd_options_object.ajaxurl,
			data,
			dataType,
			beforeSend,
			complete,
			success,
		});
	};

	const deleteUser = async () => {
		if (!userId) return false;

		const data = {
			action: `${nd_lgpd_options_object.nd_lgdp_prefix}delete_user`,
		};

		const success = async (response) => {
			console.log(response);
			if (response.status && response.msg) {
				alert(response.msg);
			}
		};

		await ndLgdpAjax(data, success);
		location.reload();
	};

	const downloadUserData = async () => {
		const data = {
			action: `${nd_lgpd_options_object.nd_lgdp_prefix}download_user_data`,
		};

		const success = async (response) => {
			const workSheet = XLSX.utils.json_to_sheet(response.data);
			var wb = XLSX.utils.book_new();

			XLSX.utils.book_append_sheet(wb, workSheet, 'Meus dados');

			XLSX.writeFile(wb, `user-${response.data[0].nickname}.xlsx`);
		};

		await ndLgdpAjax(data, success);
		// location.reload();
	};


	$(document).on('click', '.nd-lgpd-delete-user-data', function (e) {
		e.preventDefault($(this).attr('data-user-id'));
		if ($(this).attr('data-user-id') === '0') {
			alert('Você não está logado!');
			return false;
		}

		const msg = $(this).attr('data-msg');
		if (confirm(`${msg}`)) {
			deleteUser();
		}
	});

	$(document).on('click', '.nd-lgpd-download-user-data', function (e) {
		e.preventDefault();
		if ($(this).attr('data-user-id') === '0') {
			alert('Você não está logado!');
			return false;
		}

		const msg = $(this).attr('data-msg');
		if (confirm(`${msg}`)) {
			downloadUserData();
		}
	});
})(jQuery);


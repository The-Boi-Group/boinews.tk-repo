jQuery(function ($) {
	var $registrationDisable = $('input[name=site_registration_disabled]'),
		$termsEnable = $('input[name=site_registration_enableterms]'),
		$termsPage = $('select[name=site_registration_terms_page]'),
		$termsText = $('textarea[name=site_registration_terms]'),
		$privacyEnable = $('input[name=site_registration_enableprivacy]'),
		$privacyPage = $('select[name=site_registration_privacy_page]'),
		$privacyText = $('textarea[name=site_registration_privacy]'),
		$recaptchaOnRegister = $('input[name=site_registration_recaptcha_enable]'),
		$recaptchaOnLogin = $('input[name=recaptcha_login_enable]');

	// Toggle disable registration
	$registrationDisable.on('click', function () {
		var $fields = $registrationDisable.closest('.form-group').nextAll('.form-group');

		if (this.checked) {
			$fields.hide();
			$recaptchaOnRegister.closest('.form-group').hide();
			$recaptchaOnRegister.triggerHandler('click');
		} else {
			$fields.show();
			$termsEnable.triggerHandler('click');
			$privacyEnable.triggerHandler('click');
			$recaptchaOnRegister.closest('.form-group').show();
			$recaptchaOnRegister.triggerHandler('click');
		}
	});

	// Toggle Terms & Conditions
	$termsEnable.on('click', function () {
		if (this.checked) {
			$termsPage.closest('.form-group').show();
			$termsPage.triggerHandler('change');
		} else {
			$termsPage.closest('.form-group').hide();
			$termsText.closest('.form-group').hide();
		}
	});

	$termsPage.on('change', function () {
		if (0 == this.value) {
			$termsText.closest('.form-group').show();
		} else {
			$termsText.closest('.form-group').hide();
		}
	});

	// Toggle Privacy Policy
	$privacyEnable.on('click', function () {
		if (this.checked) {
			$privacyPage.closest('.form-group').show();
			$privacyPage.triggerHandler('change');
		} else {
			$privacyPage.closest('.form-group').hide();
			$privacyText.closest('.form-group').hide();
		}
	});

	$privacyPage.on('change', function () {
		if (0 == this.value) {
			$privacyText.closest('.form-group').show();
		} else {
			$privacyText.closest('.form-group').hide();
		}
	});

	// Toggle ReCAPTCHA.
	$recaptchaOnRegister.add($recaptchaOnLogin).on('click', function () {
		var $key = $('input[name=site_registration_recaptcha_sitekey]'),
			$secret = $('input[name=site_registration_recaptcha_secretkey]'),
			$globally = $('input[name=site_registration_recaptcha_use_globally]'),
			$wrapper = $key.add($secret).add($globally).closest('.form-group');

		if (
			// ReCAPTCHA during registration config might be hidden if registration is disabled.
			($recaptchaOnRegister.is(':visible') && $recaptchaOnRegister[0].checked) ||
			$recaptchaOnLogin[0].checked
		) {
			$wrapper.show();
		} else {
			$wrapper.hide();
		}
	});

	// Trigger toggle disable registration handler.
	$registrationDisable.triggerHandler('click');
});

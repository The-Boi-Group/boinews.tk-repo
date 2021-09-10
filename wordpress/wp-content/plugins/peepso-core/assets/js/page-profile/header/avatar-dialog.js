import $ from 'jquery';
import { ajax, dialog, hooks } from 'peepso';
import { profile as profileData } from 'peepsodata';

const UPLOAD_MAXSIZE = +peepsodata.upload_size;

const PROFILE_ID = +profileData.id;
const IMG_AVATAR_DEFAULT = profileData.img_avatar_default;
const AVATAR_NONCE = profileData.avatar_nonce;
const AVATAR_UPLOAD_URL = `${peepsodata.ajaxurl_legacy}profile.upload_avatar?avatar`;
const AVATAR_TEXT_ERROR_FILETYPE = profileData.text_error_filetype;
const AVATAR_TEXT_ERROR_FILESIZE = profileData.text_error_filesize;

let hasAvatar = +profileData.has_avatar,
	imgAvatar,
	imgAvatarOriginal,
	popup,
	$container,
	$upload,
	$uploadFile,
	$remove,
	$gravatar,
	$done,
	$cropStart,
	$cropCancel,
	$cropConfirm,
	$error,
	$loading,
	$hasAvatar,
	$noAvatar,
	$imgOriginal,
	$imgPreview,
	cropCoords;

// Newly uploaded image is in temporary state until user finalize it.
let imgIsTemporary = false;

// Flag for gravatar image.
let imgIsGravatar = false;

// Crop data cache storage for later use.
let imgCropData = null;

/**
 * Initialize popup elements.
 */
function init() {
	$container = popup.$el;
	$upload = $container.find('.ps-js-upload').on('click', upload);
	$uploadFile = uploadInit();
	$remove = $container.find('.ps-js-remove').on('click', remove);
	$gravatar = $container.find('.ps-js-gravatar').on('click', useGravatar);
	$done = $container.find('.ps-js-submit').on('click', finalize);
	$cropStart = $container.find('.ps-js-btn-crop').on('click', cropStart);
	$cropCancel = $container.find('.ps-js-btn-crop-cancel').on('click', cropCancel);
	$cropConfirm = $container.find('.ps-js-btn-crop-save').on('click', cropConfirm);
	$error = $container.find('.ps-js-error').hide();
	$loading = $container.find('.ps-js-loading').hide();
	$hasAvatar = $container.find('.ps-js-has-avatar');
	$noAvatar = $container.find('.ps-js-no-avatar');
	$imgOriginal = $container.find('.ps-js-original');
	$imgPreview = $container.find('.ps-js-preview');

	// Set initial state.
	update.apply(null, hasAvatar ? [profileData.img_avatar, profileData.img_avatar_original] : []);

	// Disable finalize button.
	$done.attr('disabled', 'disabled');
}

/**
 * Update avatar image shown on the dialog.
 *
 * @param {?string} avatar
 * @param {?string} original
 */
function update(avatar, original) {
	if (!avatar) {
		$remove.hide();
		$hasAvatar.hide();
		$noAvatar.show();

		hasAvatar = false;
		imgAvatar = IMG_AVATAR_DEFAULT;
		imgAvatarOriginal = null;

		$imgPreview.attr('src', imgAvatar);
		$imgOriginal.removeAttr('src');
	} else {
		$remove.show();
		$noAvatar.hide();
		$hasAvatar.show();

		let cacheBust = '?nocache-' + new Date().getTime();

		hasAvatar = true;
		imgAvatar = avatar;

		$imgPreview.attr('src', `${imgAvatar}${cacheBust}`);
		if (original) {
			imgAvatarOriginal = original;
			$imgOriginal.attr('src', `${imgAvatarOriginal}${cacheBust}`);
		}
	}

	// Reset crop.
	$cropStart.show();
	$cropCancel.hide();
	$cropConfirm.hide();
}

/**
 * Upload a new cover image.
 *
 * @param {Event} e
 */
function upload(e) {
	e.preventDefault();

	// Reset input file value before use to prevent the need to replace the element.
	// https://github.com/blueimp/jQuery-File-Upload/wiki/Frequently-Asked-Questions#why-is-the-file-input-field-cloned-and-replaced-after-each-selection
	// https://stackoverflow.com/questions/1703228/how-can-i-clear-an-html-file-input-with-javascript
	$uploadFile[0].value = null;

	// Simulate user click.
	$uploadFile.trigger('click');
}

function uploadInit() {
	let name = 'profile-avatar-upload',
		accept = 'image/*',
		css = { position: 'absolute', opacity: 0, height: 1, width: 1 },
		html = `<input type="file" name="filedata" accept="${accept}" data-name="${name}" />`,
		$file = $(html).css(css);

	$file.appendTo(document.body);

	if ($file.psFileupload) {
		$file.psFileupload({
			formData: { user_id: PROFILE_ID, _wpnonce: AVATAR_NONCE },
			dataType: 'json',
			url: AVATAR_UPLOAD_URL,
			replaceFileInput: false,
			add(e, data) {
				let file = data.files[0];
				if (!file.type.match(/image\/(jpe?g|png|webp)$/i)) {
					$error.html(AVATAR_TEXT_ERROR_FILETYPE).show();
				} else if (file.size > UPLOAD_MAXSIZE) {
					$error.html(AVATAR_TEXT_ERROR_FILESIZE).show();
				} else {
					$error.hide();
					data.submit();
				}
			},
			done(e, data) {
				let json = data.result;

				if (json.success) {
					let avatar = json.data && json.data.image_url,
						original = json.data && json.data.orig_image_url;

					update(avatar, original);

					// Mark newly uploaded image as temporary.
					imgIsTemporary = true;
					imgIsGravatar = false;
					$done.removeAttr('disabled');
				} else if (json.errors) {
					$error.html(json.errors[0]).show();
				}
			}
		});
	}

	return $file;
}

/**
 * Remove current avatar.
 *
 * @param {Event} e
 */
function remove(e) {
	e.preventDefault();

	let params = { user_id: PROFILE_ID, _wpnonce: AVATAR_NONCE };
	ajax.post('profile.remove_avatar', params).then(json => {
		if (json.success) {
			update(null);

			// Avatar removal is done immediately.
			popup.hide();
			imgIsTemporary = false;
			$done.attr('disabled', 'disable');
			hooks.doAction('profile_avatar_updated', PROFILE_ID, IMG_AVATAR_DEFAULT);
		}
	});
}

/**
 * Use gravatar image.
 *
 * @param {Event} e
 */
function useGravatar(e) {
	e.preventDefault();

	let params = { user_id: PROFILE_ID, _wpnonce: AVATAR_NONCE };
	ajax.post('profile.use_gravatar', params).then(json => {
		if (json.success) {
			let avatar = json.data && json.data.image_url;

			update(avatar, avatar);

			imgIsTemporary = true;
			imgIsGravatar = true;
			$done.removeAttr('disabled');
		}
	});
}

/**
 * Finalize current avatar.
 *
 * @param {Event} e
 */
var finalizing;
function finalize(e) {
	e.preventDefault();

	if (finalizing) {
		return;
	}

	// Do nothing if it is not a temporary image.
	if (imgIsTemporary) {
		let params = {
			user_id: PROFILE_ID,
			use_gravatar: imgIsGravatar ? 1 : 0,
			_wpnonce: AVATAR_NONCE
		};

		finalizing = true;
		ajax.post('profile.confirm_avatar', params)
			.then(json => {
				if (json.success) {
					let avatar = json.data && json.data.image_url;

					popup.hide();

					// Change temporary image status.
					imgIsTemporary = false;
					$done.attr('disabled', 'disabled');
					hooks.doAction('profile_avatar_updated', PROFILE_ID, avatar);
				}
			})
			.always(function () {
				finalizing = false;
			});
	} else if (imgCropData) {
		let params = $.extend(
			imgCropData,
			{ user_id: PROFILE_ID, _wpnonce: AVATAR_NONCE },
			{ tmp: '', preview: '' }
		);

		finalizing = true;
		ajax.post('profile.crop', params)
			.then(json => {
				if (json.success) {
					let avatar = json.data && json.data.image_url;

					popup.hide();

					// Immediately apply avatar for non-temporary image.
					// imgCropData = null;
					// $done.attr('disabled', 'disabled');
					// hooks.doAction('profile_avatar_updated', PROFILE_ID, avatar);

					// Force reload to clear image cache.
					window.location.reload(true);
				}
			})
			.always(function () {
				finalizing = false;
			});
	} else {
		popup.hide();
	}
}

/**
 * Initalize avatar cropping.
 *
 * @param {Event} e
 */
function cropStart(e) {
	e.preventDefault();

	// Attach cropping layer.
	window.ps_crop.init({
		elem: $imgOriginal,
		change: coords => {
			// Save crop coordinates for later use.
			cropCoords = cropGetCoords($imgOriginal[0], coords);
		}
	});

	// Toggle cropping buttons.
	$cropStart.hide();
	$cropCancel.show();
	$cropConfirm.show();
}

/**
 * Cancel avatar cropping.
 *
 * @param {Event} e
 */
function cropCancel(e) {
	e.preventDefault();

	// Detach cropping layer.
	window.ps_crop.detach($imgOriginal);

	// Reset cropping buttons.
	$cropStart.show();
	$cropCancel.hide();
	$cropConfirm.hide();
}

/**
 * Confirm avatar cropping.
 *
 * @param {Event} e
 */
function cropConfirm(e) {
	e.preventDefault();

	// Detach cropping layer.
	window.ps_crop.detach($imgOriginal);

	// Reset cropping buttons.
	$cropStart.show();
	$cropCancel.hide();
	$cropConfirm.hide();

	let cropData = {
		x: cropCoords.x1,
		y: cropCoords.y1,
		x2: cropCoords.x2,
		y2: cropCoords.y2,
		width: cropCoords.width,
		height: cropCoords.height
	};

	// Set flag for newly uploaded image.
	if (imgIsTemporary) {
		cropData.tmp = 1;
	} else {
		cropData.preview = 1;
	}

	let params = $.extend(cropData, { user_id: PROFILE_ID, _wpnonce: AVATAR_NONCE });
	ajax.post('profile.crop', params).then(json => {
		if (json.success) {
			update(json.data.image_url);

			// Immediately apply avatar for non-temporary image.
			if (!imgIsTemporary) {
				imgCropData = cropData;
				$done.removeAttr('disabled');
			}
		}
	});
}

/**
 * Get crop measurements.
 *
 * @param {Element} img
 * @param {Object} coords
 * @returns {Object|false}
 */
function cropGetCoords(img, coords) {
	let $img = $(img),
		ratio = 1,
		maxWH = 800,
		resize = false,
		width,
		height,
		params;

	// Calculate ratio of resized image on this dialog relative to its actual dimension.
	if (img.naturalWidth) {
		width = img.naturalWidth || $img.width();
		height = img.naturalHeight || $img.height();

		// Reduce large dimension images.
		if (width > maxWH || height > maxWH) {
			ratio = maxWH / Math.max(width, height);
			width = width * ratio;
			height = height * ratio;
			resize = true;
		}

		ratio = width / $img.width();
	}

	params = {
		x1: Math.floor(ratio * coords.x),
		y1: Math.floor(ratio * coords.y),
		x2: Math.floor(ratio * (coords.x + coords.width)),
		y2: Math.floor(ratio * (coords.y + coords.height))
	};

	if (resize) {
		params.width = width;
		params.height = height;
	}

	return params;
}

/**
 *
 */

export default function () {
	if (!popup) {
		popup = dialog(profileData.template_avatar, { wide: true, destroyOnClose: false }).show();

		init();
	}

	return popup;
}

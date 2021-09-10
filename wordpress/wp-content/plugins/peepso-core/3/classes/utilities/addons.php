<?php

class PeepSo3_Helper_Addons {

    public static function get_addons() {

        $has_new = FALSE;

        if(isset($_REQUEST['nocache'])) {
            PeepSo3_Mayfly::del('bundle_info');
        }

        $url = PeepSoLicense::PEEPSO_HOME;
        $bundle_info = PeepSo3_Mayfly::get('bundle_info');

        if (!$bundle_info) {
            $url .= '/?product_bundles_list';
            $request = wp_remote_get($url, ['timeout' => 15, 'sslverify' => TRUE]);

            if (is_wp_error($request)) {
                $request = wp_remote_post($url, ['timeout' => 15, 'sslverify' => FALSE]);
            }

            if (!is_wp_error($request)) {
                $bundle_info = json_decode(wp_remote_retrieve_body($request));
                PeepSo3_Mayfly::set('bundle_info', $bundle_info, 3600);

                foreach($bundle_info as $item) {
                    if(isset($item->new)) {
                        $has_new = $item->id;
                        break;
                    }
                }

                if($has_new) {
                    PeepSo3_Mayfly_Int::set('installer_has_new', $has_new);
                } else {
                    PeepSo3_Mayfly_Int::del('installer_has_new');
                }
            }
        }

        return $bundle_info;
    }
}
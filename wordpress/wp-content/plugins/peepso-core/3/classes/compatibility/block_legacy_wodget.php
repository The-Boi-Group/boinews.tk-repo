<?php

class PeepSo3_Block_Legacy_Widget {

    private static $instance;

    public static function get_instance()
    {
        return isset(self::$instance) ? self::$instance : self::$instance = new self;
    }

    private function __construct() {

        add_filter('peepso_fix_block_legacy_widget', function($args, $instance) {

            if(!isset($_GET['legacy-widget-preview'])) {
                return $args;
            }

//var_dump($data['instance']);
            $params = [0=>$args,'instance'=>$instance];

            $params = apply_filters('peepso_legacy_widget_preview_args', $params);
            $args = $params[0];

            $args['before_widget'] = preg_replace('/class="/', 'class="ps-widget--preview ', $args['before_widget'],1);

            return $args;
        },10,2);

        if(PeepSo::is_dev_mode('fix_widget_preview')) {

            remove_action('init', 'register_block_core_legacy_widget');

            add_action('init', 'peepso_register_block_core_legacy_widget');

            function peepso_register_block_core_legacy_widget()
            {
                register_block_type_from_metadata(
                    ABSPATH . WPINC . '/blocks/legacy-widget',
                    array(
                        'render_callback' => 'peepso_render_block_core_legacy_widget',
                    )
                );
            }

            function peepso_render_block_core_legacy_widget($attributes)
            {
                global $wp_widget_factory;

                if (isset($attributes['id'])) {
                    $sidebar_id = wp_find_widgets_sidebar($attributes['id']);
                    return wp_render_widget($attributes['id'], $sidebar_id);
                }

                if (!isset($attributes['idBase'])) {
                    return '';
                }

                $id_base = $attributes['idBase'];
                if (method_exists($wp_widget_factory, 'get_widget_key') && method_exists($wp_widget_factory, 'get_widget_object')) {
                    $widget_key = $wp_widget_factory->get_widget_key($id_base);
                    $widget_object = $wp_widget_factory->get_widget_object($id_base);
                } else {
                    $widget_key = gutenberg_get_widget_key($id_base);
                    $widget_object = gutenberg_get_widget_object($id_base);
                }

                if (!$widget_key || !$widget_object) {
                    return '';
                }

                if (isset($attributes['instance']['encoded'], $attributes['instance']['hash'])) {
                    $serialized_instance = base64_decode($attributes['instance']['encoded']);
                    if (wp_hash($serialized_instance) !== $attributes['instance']['hash']) {
                        return '';
                    }
                    $instance = unserialize($serialized_instance);
                } else {
                    $instance = array();
                }

                $args = array(
                    'widget_id' => $widget_object->id,
                    'widget_name' => $widget_object->name,
                );

                $args = apply_filters('peepso_fix_block_legacy_widget', $args, $instance);

                ob_start();
                the_widget($widget_key, $instance, $args);
                return ob_get_clean();
            }
        }
    }
}

PeepSo3_Block_Legacy_Widget::get_instance();

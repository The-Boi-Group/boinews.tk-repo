<?php

class PeepSoConfigSectionAppearance extends PeepSoConfigSectionAbstract
{
    // Builds the groups array
    public function register_config_groups()
    {
        $this->context='left';
        $this->members();
        $this->profiles();

        $this->context='right';
        $this->template();
        $this->general();
        $this->registration();
    }

    private function profiles()
    {
        // Display name style
        $options = apply_filters('peepso_filter_display_name_styles', []);

        $this->args('options', $options);
        $this->set_field(
            'system_display_name_style',
            __('Display name style', 'peepso-core'),
            'select'
        );

        // Allow User To Override Name Setting
        $this->set_field(
            'system_override_name',
            __('Users can override this setting', 'peepso-core'),
            'yesno_switch'
        );

        // Allow Profile Deletion
        $this->args('descript', __('Users can permanently delete their profiles'));
        $this->set_field(
            'site_registration_allowdelete',
            __('Profile deletion', 'peepso-core'),
            'yesno_switch'
        );

        // Profile Likes
        $this->args('descript',__('Users can "like" each other\'s  profiles', 'peepso-core'));
        $this->set_field(
            'site_likes_profile',
            __('Profile likes', 'peepso-core'),
            'yesno_switch'
        );

        // Profile View Count
        $this->args('descript','Admins will see the count unless it\'s set to "hidden"');
        $this->args('options', [
            99 => __('Hidden', 'peepso-core'),
            10 => __('Public', 'peepso-core'),
            20 => __('Site Members', 'peepso-core'),
            40 => __('Only me', 'peepso-core')
        ]);

        $this->set_field(
            'profile_view_count_privacy_default',
            __('Profile view count - default privacy', 'peepso-core'),
            'select'
        );

        // Always link to PeepSo Profile
        $this->set_field(
            'always_link_to_peepso_profile',
            __('Always link to PeepSo profile', 'peepso-core'),
            'yesno_switch'
        );

        // Use Peepso names
        $this->set_field(
            'use_name_everywhere',
            __('Use PeepSo names everywhere', 'peepso-core'),
            'yesno_switch'
        );


        $this->args('descript','The PeepSo Integrated Widgets feature will be phased out in the future. Please migrate your widgets / blocks to other positions');
        $this->set_field(
            'legacy_peepso_widget_position',
            __('Legacy PeepSo Integrated Widgets area', 'peepso-core'),
            'yesno_switch'
        );


        // Do not minimize cover

        /** AVATARS & Covers **/
        // # Separator Avatars
        $this->set_field(
            'separator_avatars',
            __('Avatars', 'peepso-core'),
            'separator'
        );

        // Use Peepso Avatars
        $this->set_field(
            'avatars_wordpress_only',
            __('Use WordPress avatars', 'peepso-core'),
            'yesno_switch'
        );

        // Use Peepso Avatars
        $this->set_field(
            'avatars_wordpress_only_desc',
            __('The users will be unable to change their avatars in their PeepSo profiles. PeepSo will inherit the avatars from your WordPress site', 'peepso-core'),
            'message'
        );

        // Use Peepso Avatars
        $this->set_field(
            'avatars_peepso_only',
            __('Use PeepSo avatars everywhere', 'peepso-core'),
            'yesno_switch'
        );

        // Use Gravatar Avatars
        $this->set_field(
            'avatars_gravatar_enable',
            __('Allow Gravatar avatars', 'peepso-core'),
            'yesno_switch'
        );

        // # Separator
        $this->set_field(
            'separator_avatars_default',
            __('Default avatars', 'peepso-core'),
            'separator'
        );

        // Use SVG avatars
        $this->args('descript', 'If no avatar is provided, PeepSo will generate an avatar based on user initials or username. The colors will be randomized, unless you decide to use grayscale');
        $this->set_field(
            'avatars_name_based',
            __('Generate name based avatars', 'peepso-core'),
            'yesno_switch'
        );


        $options = [
            0 => 'Black',
            50 => 'Very dark',
            100 => 'Dark',
            125 => 'Medium',
            150 => 'Light (default)',
            200 => 'Very light',
            255 => 'White',
        ];

        $this->args('options', $options);
        $this->set_field(
            'avatars_name_based_background_color',
            __('Background', 'peepso-core'),
            'select'
        );

        $this->set_field(
            'avatars_name_based_background_grayscale',
            __('Grayscale background', 'peepso-core'),
            'yesno_switch'
        );

        $options = [
            '0' => 'Black',
            '50' => 'Very dark (default)',
            '100' => 'Dark',
            '125' => 'Medium',
            '150' => 'Light',
            '200' => 'Very light',
            '255' => 'White',
        ];

        $this->args('options', $options);
        $this->set_field(
            'avatars_name_based_font_color',
            __('Font color', 'peepso-core'),
            'select'
        );

        $this->set_field(
            'avatars_name_based_preview',
            'Preview',
            'yesno_switch'
        );


        // # Separator
        $this->set_field(
            'separator_covers',
            __('Covers', 'peepso-core'),
            'separator'
        );

        $this->args('descript', __('By default the full cover displays only in the header of the "Stream" section'));
        $this->set_field(
            'always_full_cover',
            __('Always use full covers', 'peepso-core'),
            'yesno_switch'
        );

        // Build Group
        $this->set_group(
            'profiles',
            __('User profiles', 'peepso-core')
        );
    }


    private function registration()
    {
        /** CUSTOM TEXT **/

        // # Separator Callout
        $this->set_field(
            'separator_callout',
            __('Customize text', 'peepso-core'),
            'separator'
        );

        // # Callout Header
        $this->set_field(
            'site_registration_header',
            __('Callout header', 'peepso-core'),
            'text'
        );

        // # Callout Text
        $this->set_field(
            'site_registration_callout',
            __('Callout text', 'peepso-core'),
            'text'
        );

        // # Button Text
        $this->set_field(
            'site_registration_buttontext',
            __('Button text', 'peepso-core'),
            'text'
        );

        /** LANDING PAGE IMAGE **/
        // # Separator Landing Page
        $this->set_field(
            'separator_landing_page',
            __('Landing page image', 'peepso-core'),
            'separator'
        );

        // # Message Logging Description
        $this->set_field(
            'suggested_message_landing_page',
            // todo: filter for landing page image size
            __('Suggested size is: 1140px x 469px.', 'peepso-core'),
            'message'
        );

        // Landing Page Image
        $default = PeepSo::get_option('landing_page_image', PeepSo::get_asset('images/landing/register-bg.jpg'));
        $landing_page = !empty($default) ? $default : PeepSo::get_asset('images/landing/register-bg.jpg');
        $this->args('value', $landing_page);
        $this->set_field(
            'landing_page_image',
            __('Selected Image', 'peepso-core'),
            'text'
        );

        $default = PeepSo::get_option('landing_page_image_default', PeepSo::get_asset('images/landing/register-bg.jpg'));
        $this->args('value', $default);
        $this->set_field(
            'landing_page_image_default',
            '',
            'text'
        );
        // Build Group
        $this->set_group(
            'registration',
            __('Registration', 'peepso-core')
        );
    }

    private function template() {
        $override_message = apply_filters('peepso_theme_override', false);
        if (is_string($override_message)) {
            $this->set_field(
                'site_css_template_override',
                $override_message,
                'message'
            );
        } else {

            // Primary CSS Template
            $options = array(
                '' => __('Light', 'peepso-core'),
            );

            $dir = plugin_dir_path(__FILE__) . '/../templates/css';

            $dir = scandir($dir);
            $from_key = array('template-', '.css');
            $to_key = array('');

            $from_name = array('_', '-');
            $to_name = array(' ', ' ');

            foreach ($dir as $file) {
                if ('template-' == substr($file, 0, 9) && !strpos($file, 'rtl') && !strpos($file, 'round')) {

                    $key = str_replace($from_key, $to_key, $file);
                    $name = str_replace($from_name, $to_name, $key);
                    $options[$key] = ucwords($name);
                }
            }

            $this->args('options', $options);
            $this->args('descript', sprintf(
                __('Pick a color from the list that suits your site best. If the list doesn???t contain the color you???re looking for you can always use %s.', 'peepso-core'),
                '<a target="_blank" href="https://peep.so/docs_css_overrides">' . __('CSS overrides', 'peepso-core') . ' <i class="fa fa-external-link"></i></a>'
            ));
            $this->set_field(
                'site_css_template',
                __('Color scheme', 'peepso-core'),
                'select'
            );

        }
        // Build Group
        $this->set_group(
            'appearance_general',
            __('Color scheme', 'peepso-core')
        );
    }
    private function general()
    {
        // Show notification icons on WP Toolbar
        $this->set_field(
            'site_show_notification_on_navigation_bar',
            __('Show notification icons on WP Toolbar', 'peepso-core'),
            'yesno_switch'
        );

        // Disable PeepSo navbar
        $this->set_field(
            'disable_navbar',
            __('Disable PeepSo navigation bar', 'peepso-core'),
            'yesno_switch'
        );

        // Disable PeepSo navbar
        $this->set_field(
            'override_admin_navbar',
            __('Replace WordPress admin navigation bar', 'peepso-core'),
            'yesno_switch'
        );

        // Post age preferences
        $options = array(
            0 => __('never', 'peepso-core'),
            -1 => __('always', 'peepso-core'),
            24 => __('if older than 24 hours', 'peepso-core'),
        );

        $this->args('options', $options);

        $this->args('descript', __('"Relative" date is a human readable date, for example "3 days ago"','peepso-core').'<br/>'.sprintf(__('"Absolute" date is a simple datestamp such as %s','peepso-core'), date(get_option('date_format').' '.get_option('time_format'))));
        $this->set_field(
            'absolute_dates',
            __('Use absolute dates', 'peepso-core'),
            'select'
        );

        $this->args('descript', __('PeepSo needs a date format without a year to let users hide their birthday year','peepso-core'));
        $this->args('default', 'F j');
        $options = array(
            'F j' => date_i18n('F j'),
            'M j' => date_i18n('M j'),
            'm d' => date_i18n('m d'),
            'm/d' => date_i18n('m/d'),
            'd/m' => date_i18n('d/m'),
            'custom' => __('Custom', 'peepso-core'),
        );
        $this->args('options', $options);

        $this->set_field(
            'date_format_no_year',
            __('Date format (no year)', 'peepso-core'),
            'select'
        );

        // Custom date format
        $this->args('default', 'F j');

        // NO LANGUAGE DOMAIN, this is a WordPress string
        $this->args('descript', str_replace('href','target="blank" href', __('<a href="https://wordpress.org/support/article/formatting-date-and-time/">Documentation on date and time formatting</a>')));

        $this->set_field(
            'date_format_no_year_custom',
            __('Custom format', 'peepso-core'),
            'text'
        );

        // Show "Powered By Peepso" Link
        $this->set_field(
            'system_show_peepso_link',
            __('Show "Powered by PeepSo" link', 'peepso-core'),
            'yesno_switch'
        );

        $this->set_field(
            'hovercards_enable',
            __('Hover Cards', 'peepso-core'),
            'yesno_switch'
        );


        // Build Group
        $this->set_group(
            'appearance_general',
            __('General', 'peepso-core')
        );
    }

    private function members()
    {
        // Default Sorting
        $options = array(
            '' => __('Alphabetical', 'peepso-core'),
            'peepso_last_activity' => __('Recently online', 'peepso-core'),
            'registered' => __('Latest members', 'peepso-core')
        );

        if (PeepSo::get_option('site_likes_profile', TRUE)) {
            $options['most_liked'] = __('Most liked', 'peepso-core');
        }

        $this->args('options', $options);

        $this->set_field(
            'site_memberspage_default_sorting',
            __('Default Sorting', 'peepso-core'),
            'select'
        );

        // Allow users to hide themselves from all user listings
        $this->args('descript', __('Users can hide from Members Page, Widgets etc', 'peepso-core'));
        $this->set_field(
            'allow_hide_user_from_user_listing',
            __('Users can hide from user listings', 'peepso-core'),
            'yesno_switch'
        );

        // allow guest access to Members listing
        $this->args('default', 0);
        $this->set_field(
            'allow_guest_access_to_members_listing',
            __('Allow guest access to members listing', 'peepso-core'),
            'yesno_switch'
        );

        // Show "Powered By Peepso" Link
        $this->set_field(
            'members_hide_before_search',
            __('Only show members when something is searched', 'peepso-core'),
            'yesno_switch'
        );

        // Show "Powered By Peepso" Link
        $this->set_field(
            'members_email_searchable',
            __('Allow searching user e-mails', 'peepso-core'),
            'yesno_switch'
        );


        // Build Group
        $this->set_group(
            'appearance_members',
            __('Member listings', 'peepso-core')
        );
    }
}

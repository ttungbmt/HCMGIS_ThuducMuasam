<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class CreateAppearanceSettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('appearance.site_logo', null);
        $this->migrator->add('appearance.site_logo_light', null);
        $this->migrator->add('appearance.site_favicon', null);
        $this->migrator->add('appearance.admin_login_background', null);
        $this->migrator->add('appearance.footer_html', null);
        $this->migrator->add('appearance.primary_font', 'Nunito, system-ui, BlinkMacSystemFont, -apple-system, sans-serif');
        $this->migrator->add('appearance.button_radius', '8px');
        $this->migrator->add('appearance.base_color', '#7C858E');
        $this->migrator->add('appearance.headline_color', '#3C4B5F');
        $this->migrator->add('appearance.primary_color', '#4099DE');
        $this->migrator->add('appearance.secondary_color', '#CC003D');
        $this->migrator->add('appearance.custom_css', null);
        $this->migrator->add('appearance.html_scripts_header', null);
        $this->migrator->add('appearance.html_scripts_footer', null);
        $this->migrator->add('appearance.html_scripts_after_body', null);
        $this->migrator->add('appearance.html_scripts_before_body', null);
        $this->migrator->add('appearance.social_facebook', 'https://facebook.com');
        $this->migrator->add('appearance.social_twitter', 'https://twitter.com');
        $this->migrator->add('appearance.social_youtube', 'https://youtube.com');
    }
}

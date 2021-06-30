<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class CreateGeneralSettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.site_name', env('APP_NAME'));
        $this->migrator->add('general.site_title', env('APP_NAME'));
        $this->migrator->add('general.site_description', null);
        $this->migrator->add('general.site_keywords', null);
        $this->migrator->add('general.site_author', 'Trương Thanh Tùng');
        $this->migrator->add('general.site_url', env('APP_URL'));
        $this->migrator->add('general.site_version', '1.0.0');
        $this->migrator->add('general.admin_email', 'admin@admin.com');
        $this->migrator->add('general.active_language', 'vi');
        $this->migrator->add('general.date_format', 'd/m/Y');
        $this->migrator->add('general.time_format', 'H:i');
        $this->migrator->add('general.timezone', 'Asia/Ho_Chi_Minh');
        $this->migrator->add('general.users_can_register', false);
        $this->migrator->add('general.default_role', 'guest');
    }
}

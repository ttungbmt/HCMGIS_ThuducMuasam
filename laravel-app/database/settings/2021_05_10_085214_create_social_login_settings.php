<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class CreateSocialLoginSettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('social_login.social_login_enable', false);
        $this->migrator->add('social_login.google_enable', null);
        $this->migrator->add('social_login.google_client_id', null);
        $this->migrator->add('social_login.google_client_secret', null);
        $this->migrator->add('social_login.facebook_enable', null);
        $this->migrator->add('social_login.facebook_client_id', null);
        $this->migrator->add('social_login.facebook_client_secret', null);
        $this->migrator->add('social_login.github_enable', null);
        $this->migrator->add('social_login.github_client_id', null);
        $this->migrator->add('social_login.github_client_secret', null);
        $this->migrator->add('social_login.twitter_enable', null);
        $this->migrator->add('social_login.twitter_client_id', null);
        $this->migrator->add('social_login.twitter_client_secret', null);
    }
}

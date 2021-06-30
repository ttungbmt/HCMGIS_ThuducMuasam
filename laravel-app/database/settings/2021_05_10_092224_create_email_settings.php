<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class CreateEmailSettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('email.email_driver', null);
        $this->migrator->add('email.email_port', null);
        $this->migrator->add('email.email_host', null);
        $this->migrator->add('email.email_username', null);
        $this->migrator->add('email.email_password', null);
        $this->migrator->add('email.email_encryption', null);
        $this->migrator->add('email.email_from_name', null);
        $this->migrator->add('email.email_from_address', null);
    }
}

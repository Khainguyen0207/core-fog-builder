<?php

namespace App\Admin\Panels\Forms;

use App\Facades\SettingHelper;
use App\Forms\BaseForm;
use App\Forms\Fields\InputField;
use App\Forms\Fields\SwitchField;
use App\Models\Setting;

class TelegramPanelForm extends BaseForm
{
    public function setup(): static
    {
        parent::setup();

        return $this
            ->model(Setting::class)
            ->setTitle('Telegram Bot Setting')
            ->add(
                'telegram_bot_token',
                InputField::class,
                InputField::make('telegram_bot_token')
                    ->setLabel('Bot Token')
                    ->setPlaceholder('Ex: 123456:ABC-DEF1234ghIkl-zyx57W2v1u123ew11')
                    ->setDefaultValue(SettingHelper::get('telegram_bot_token') ?? '')
                    ->setSpan(2)
                    ->isRequired()
            )
            ->add(
                'telegram_webhook_url',
                InputField::class,
                InputField::make('telegram_webhook_url')
                    ->setLabel('Webhook URL')
                    ->setPlaceholder('Ex: https://yourdomain.com/webhook/telegram')
                    ->setDefaultValue(SettingHelper::get('telegram_webhook_url') ?? '')
                    ->setSpan(2)
            )
            ->add(
                'telegram_chat_id',
                InputField::class,
                InputField::make('telegram_chat_id')
                    ->setLabel('Chat ID')
                    ->setPlaceholder('Ex: -1003848646873')
                    ->setDefaultValue(SettingHelper::get('telegram_chat_id') ?? '')
                    ->helperText('<a href="https://t.me/+UkakVQvNAaI4YzM1" class="text-primary">Join group telegram notification here (default)</a>')
                    ->isRequired()
            )
            ->add(
                'is_active_telegram',
                SwitchField::class,
                SwitchField::make('is_active_telegram')
                    ->setLabel('Enable Telegram?')
                    ->setDefaultValue(SettingHelper::get('is_active_telegram') ?? '0')
                    ->setSpan(1)
            );
    }
}

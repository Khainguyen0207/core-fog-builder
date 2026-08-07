<?php

namespace App\Admin\Panels\Forms;

use App\Facades\SettingHelper;
use App\Forms\BaseForm;
use App\Forms\Fields\InputField;
use App\Models\Setting;

class InformationSystemForm extends BaseForm
{
    public function setup(): static
    {
        parent::setup();

        return $this
            ->model(Setting::class)
            ->setTitle('Information System Settings')
            ->add(
                'system_email',
                InputField::class,
                InputField::make('system_email')
                    ->setLabel('Email')
                    ->setPlaceholder('Ex: contact@example.com')
                    ->setDefaultValue(SettingHelper::get('system_email') ?? '')
                    ->isRequired()
            )
            ->add(
                'system_hotline',
                InputField::class,
                InputField::make('system_hotline')
                    ->setLabel('Hotline')
                    ->setPlaceholder('Ex: 0901234567')
                    ->setDefaultValue(SettingHelper::get('system_hotline') ?? '')
                    ->isRequired()
            )
            ->add(
                'system_zalo_support',
                InputField::class,
                InputField::make('system_zalo_support')
                    ->setLabel('Zalo Support')
                    ->setPlaceholder('Ex: 0901234567')
                    ->setDefaultValue(SettingHelper::get('system_zalo_support') ?? '')
                    ->isRequired()
            )
            ->add(
                'system_address',
                InputField::class,
                InputField::make('system_address')
                    ->setLabel('Address')
                    ->setSpan(2)
                    ->setPlaceholder('Ex: 123 Main Street, City')
                    ->setDefaultValue(SettingHelper::get('system_address') ?? '')
                    ->isRequired()
            )
            ->add(
                'social_facebook',
                InputField::class,
                InputField::make('social_facebook')
                    ->setLabel('Facebook URL')
                    ->setPlaceholder('Ex: https://facebook.com/yourpage')
                    ->setDefaultValue(SettingHelper::get('social_facebook') ?? '')
            )
            ->add(
                'social_instagram',
                InputField::class,
                InputField::make('social_instagram')
                    ->setLabel('Instagram URL')
                    ->setPlaceholder('Ex: https://instagram.com/yourpage')
                    ->setDefaultValue(SettingHelper::get('social_instagram') ?? '')
            )
            ->add(
                'social_thread',
                InputField::class,
                InputField::make('social_thread')
                    ->setLabel('Threads URL')
                    ->setPlaceholder('Ex: https://threads.net/yourpage')
                    ->setDefaultValue(SettingHelper::get('social_thread') ?? '')
            )
            ->add(
                'social_tiktok',
                InputField::class,
                InputField::make('social_tiktok')
                    ->setLabel('TikTok URL')
                    ->setPlaceholder('Ex: https://tiktok.com/yourpage')
                    ->setDefaultValue(SettingHelper::get('social_tiktok') ?? '')
            );
    }
}

<?php

namespace App\Admin\Panels\Forms;

use App\Facades\SettingHelper;
use App\Forms\BaseForm;
use App\Forms\Fields\InputField;
use App\Forms\Fields\SelectField;
use App\Models\Setting;

class SePayPanelForm extends BaseForm
{
    public function setup(): static
    {
        parent::setup();

        return $this
            ->model(Setting::class)
            ->setTitle('Customer')
            ->add(
                'receiver_name',
                InputField::class,
                InputField::make('receiver_name')
                    ->setLabel('Receiver Name')
                    ->setPlaceholder('Ex: Nguyen Van A')
                    ->setDefaultValue(SettingHelper::get('receiver_name') ?? '')
                    ->isRequired()
            )
            ->add(
                'bank_name',
                SelectField::class,
                SelectField::make('bank_name')
                    ->setLabel('Bank Name')
                    ->setDefaultValue(SettingHelper::get('bank_name') ?? '')
                    ->setOptions(collect(config('banks'))->mapWithKeys(fn ($value, $key) => [$key => $value])->toArray())
                    ->isRequired()
            )
            ->add(
                'account_number',
                InputField::class,
                InputField::make('account_number')
                    ->setLabel('Account Number')
                    ->setPlaceholder('Ex: 0123...')
                    ->setDefaultValue(SettingHelper::get('account_number') ?? '')
                    ->isRequired()
            )
            ->add(
                'payment_provider',
                SelectField::class,
                SelectField::make('payment_provider')
                    ->setLabel('Active Provider')
                    ->setDefaultValue(SettingHelper::get('payment_provider') ?? 1)
                    ->setOptions([
                        'sepay' => 'Sepay',
                    ])
                    ->isRequired()
            )
            ->add(
                'sepay_api_token',
                InputField::class,
                InputField::make('sepay_api_token')
                    ->setLabel('SePay API Token')
                    ->setSpan(2)
                    ->setDefaultValue(SettingHelper::get('sepay_api_token') ?? '')
                    ->setPlaceholder('Enter API Token...')
                    ->isRequired()
            );
    }
}

<?php

namespace Database\Seeders;

use App\Models\EmailTemplate;
use Illuminate\Database\Seeder;

class EmailTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            [
                'name' => 'Welcome New Customer',
                'description' => 'Email template sent when a new customer signs up or visits for the first time.',
                'html_content' => '<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px;">
                    <h2 style="color: #ff6b6b; text-align: center;">Welcome to Moto Service!</h2>
                    <p>Hello <strong>{{customer_name}}</strong>,</p>
                    <p>Thank you for trusting us and choosing our service.</p>
                    <p>Your contact details: <br/> Email: <strong>{{customer_email}}</strong> <br/> Phone: <strong>{{customer_phone}}</strong></p>
                    <p>Your membership code: <strong>{{membership_code}}</strong></p>
                    <p>We hope you have a great experience with us. Feel free to contact us whenever you need support.</p>
                    <br>
                    <p style="color: #888; font-size: 12px; text-align: center;">Moto Service Manager Team</p>
                </div>',
                'file_path' => null,
            ],
            [
                'name' => 'Customer Appreciation - Discount Voucher',
                'description' => 'Email template to thank customers and share a discount code for bike maintenance.',
                'html_content' => '<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; background-color: #f9f9f9; border-radius: 8px;">
                    <h2 style="color: #4CAF50; text-align: center;">A Special Gift for You!</h2>
                    <p>Dear <strong>{{customer_name}}</strong>,</p>
                    <p>To thank you for being with us, we are sending you a special discount code for your next maintenance visit.</p>
                    <div style="background-color: #fff; border: 2px dashed #4CAF50; padding: 15px; text-align: center; margin: 20px 0;">
                        <span style="font-size: 24px; font-weight: bold; color: #4CAF50;">DISCOUNT2026</span>
                    </div>
                    <p>Please show this code to our technician when you visit the shop (Phone: {{customer_phone}}).</p>
                    <p>See you again soon!</p>
                    <hr style="border: none; border-top: 1px solid #eee;">
                    <p style="color: #888; font-size: 12px; text-align: center;">Moto Service Manager Team</p>
                </div>',
                'file_path' => null,
            ],
        ];

        foreach ($templates as $template) {
            EmailTemplate::firstOrCreate(['name' => $template['name']], $template);
        }
    }
}

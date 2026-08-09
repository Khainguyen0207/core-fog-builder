<?php

namespace Modules\Communications\Http\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Modules\Communications\Admin\Forms\EmailTemplateForm;
use Modules\Communications\Admin\Tables\EmailTemplateTable;
use Modules\Communications\Http\Requests\EmailTemplateRequest;

class EmailTemplateController extends Controller
{
    public function index(EmailTemplateTable $table)
    {
        return $table->renderTable();
    }

    public function show(EmailTemplate $emailTemplate)
    {
        return EmailTemplateForm::make()->createWithModel($emailTemplate)->renderForm();
    }

    public function update(EmailTemplateRequest $request, EmailTemplate $emailTemplate)
    {
        $data = $request->validated();

        if ($file = $request->file('file_html')) {
            $userId = Auth::id();
            $fileName = Str::uuid().'.blade.php';
            $storagePath = "email-templates/{$userId}/{$fileName}";

            // Xóa file cũ nếu có
            if ($emailTemplate->file_path && Storage::exists($emailTemplate->file_path)) {
                Storage::delete($emailTemplate->file_path);
            }

            Storage::put($storagePath, file_get_contents($file->getRealPath()));

            $data['file_path'] = $storagePath;
            $data['html_content'] = file_get_contents($file->getRealPath());
        }

        $emailTemplate->update($data);

        return redirect()->back()->with('success', 'Email Template updated successfully.');
    }

    public function destroy(EmailTemplate $emailTemplate)
    {
        // Xóa file trong storage nếu có
        if ($emailTemplate->file_path && Storage::exists($emailTemplate->file_path)) {
            Storage::delete($emailTemplate->file_path);
        }

        $emailTemplate->delete();

        return response()->json([
            'error' => false,
            'data' => null,
            'message' => 'Email Template deleted successfully',
        ]);
    }

    public function preview(EmailTemplate $emailTemplate)
    {
        $variables = [
            'customer_name' => 'John Carter',
            'customer_email' => 'john.carter@example.com',
            'customer_phone' => '+1 415 555 0147',
            'membership_code' => 'GOLD-2026-US',
        ];

        $rawHtml = $this->resolveHtmlContent($emailTemplate);
        $html = $this->replaceTemplateVariables($rawHtml, $variables);

        return view('communications::admin.pages.email.preview', [
            'emailTemplate' => $emailTemplate,
            'html' => $html,
            'variables' => $variables,
        ]);
    }

    private function resolveHtmlContent(EmailTemplate $template): string
    {
        if (! empty($template->file_path) && Storage::exists($template->file_path)) {
            return Storage::get($template->file_path);
        }

        return $template->html_content ?? '';
    }

    private function replaceTemplateVariables(string $content, array $variables): string
    {
        foreach ($variables as $name => $value) {
            $pattern = '/{{\s*'.preg_quote((string) $name, '/').'\s*}}/';
            $content = preg_replace($pattern, (string) $value, $content) ?? $content;
        }

        return $content;
    }
}

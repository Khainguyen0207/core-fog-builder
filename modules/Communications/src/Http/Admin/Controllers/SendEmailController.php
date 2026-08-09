<?php

namespace Modules\Communications\Http\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Jobs\SendTemplateEmailJob;
use App\Models\EmailTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Modules\Communications\Admin\Tables\SendEmailUserTable;

class SendEmailController extends Controller
{
    public function index(SendEmailUserTable $table)
    {
        $templates = EmailTemplate::all(['id', 'name']);

        $table->setup();

        return view('communications::admin.pages.email.send', [
            'table' => $table,
            'name' => $table->getName(),
            'templates' => $templates,
        ]);
    }

    public function getTemplatePreview($id)
    {
        $template = EmailTemplate::find($id);
        if (! $template) {
            return response()->json(['error' => true, 'message' => 'Template not found']);
        }

        return response()->json([
            'error' => false,
            'data' => [
                'name' => $template->name,
                'description' => $template->description,
                'html' => $template->html_content,
            ],
        ]);
    }

    public function send(Request $request)
    {
        $request->validate([
            'template_id' => 'required|exists:email_templates,id',
            'user_ids' => 'required|string',
        ]);

        $userIds = array_filter(explode(',', $request->user_ids));

        if (empty($userIds)) {
            return response()->json(['error' => true, 'message' => 'Please select at least one customer']);
        }

        $template = EmailTemplate::findOrFail($request->template_id);

        try {
            SendTemplateEmailJob::dispatch($userIds, $template->id);

            return response()->json([
                'error' => false,
                'message' => 'Emails are being processed in the background.',
            ]);
        } catch (\Exception $e) {
            Log::error('Dispatch SendTemplateEmailJob failed: '.$e->getMessage());

            return response()->json([
                'error' => true,
                'message' => 'Failed to queue emails.',
            ], 500);
        }
    }
}

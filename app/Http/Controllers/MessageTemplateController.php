<?php

namespace App\Http\Controllers;

use App\Models\MessageTemplate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MessageTemplateController extends Controller
{
    public function index(): View
    {
        $templates = MessageTemplate::orderBy('code')->get();
        return view('message-templates.index', compact('templates'));
    }

    public function update(Request $request, MessageTemplate $messageTemplate): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:100'],
            'body' => ['required', 'string', 'max:2000'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $messageTemplate->update($validated);

        return back()->with('success', "Plantilla '{$messageTemplate->title}' actualizada.");
    }
}

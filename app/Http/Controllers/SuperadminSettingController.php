<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SuperadminSettingController extends Controller
{
    /**
     * Display the settings page with WA template editor.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $waTemplate = Setting::get('wa_template',
            "Selamat {sesi} kak\nIzin Mengingatkan kak, besok hari {hari} Tanggal {tanggal} ada kelas {program} pada pukul {jam} di Elips Academy {cabang}. Terimakasih🙏"
        );

        return view('superadmin.setting.index', [
            'user' => $user,
            'waTemplate' => $waTemplate,
        ]);
    }

    /**
     * Update the WA template setting.
     */
    public function updateWaTemplate(Request $request): RedirectResponse
    {
        $request->validate([
            'wa_template' => 'required|string|max:2000',
        ], [
            'wa_template.required' => 'Template pesan WhatsApp tidak boleh kosong.',
            'wa_template.max' => 'Template pesan tidak boleh lebih dari 2000 karakter.',
        ]);

        Setting::set('wa_template', $request->input('wa_template'));

        return redirect()
            ->route('superadmin.setting.index')
            ->with('success', 'Template WhatsApp berhasil diperbarui.');
    }
}

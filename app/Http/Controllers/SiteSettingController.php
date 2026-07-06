<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use Illuminate\Http\Request;

class SiteSettingController extends Controller
{
    public function edit()
    {
        $settings = [
            'ministry_name'   => SiteSetting::get('ministry_name'),
            'department_name' => SiteSetting::get('department_name'),
            'welcome_message' => SiteSetting::get('welcome_message'),
        ];

        return view('site-settings.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'ministry_name'   => ['required', 'string', 'max:255'],
            'department_name' => ['required', 'string', 'max:255'],
            'welcome_message' => ['nullable', 'string', 'max:500'],
        ], [
            'ministry_name.required'   => 'ກະລຸນາປ້ອນຊື່ກະຊວງ.',
            'department_name.required' => 'ກະລຸນາປ້ອນຊື່ກົມ.',
        ]);

        foreach ($validated as $key => $value) {
            SiteSetting::set($key, $value);
        }

        return redirect()->route('site-settings.edit')
            ->with('success', 'ບັນທຶກການຕັ້ງຄ່າສຳເລັດແລ້ວ');
    }
}

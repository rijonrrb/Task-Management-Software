<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SiteSettingController extends Controller
{
    public function index()
    {
        $settings = [
            'site_name' => SiteSetting::get('site_name', config('app.name')),
            'site_tagline' => SiteSetting::get('site_tagline', ''),
            'site_email' => SiteSetting::get('site_email', ''),
            'site_phone' => SiteSetting::get('site_phone', ''),
            'site_address' => SiteSetting::get('site_address', ''),
            'site_logo' => SiteSetting::get('site_logo', ''),
            'site_favicon' => SiteSetting::get('site_favicon', ''),
            'footer_text' => SiteSetting::get('footer_text', ''),
            'maintenance_mode' => SiteSetting::get('maintenance_mode', '0'),
            'maintenance_message' => SiteSetting::get('maintenance_message', 'We are currently performing maintenance. Please check back soon.'),
            'social_facebook' => SiteSetting::get('social_facebook', ''),
            'social_twitter' => SiteSetting::get('social_twitter', ''),
            'social_instagram' => SiteSetting::get('social_instagram', ''),
            'social_linkedin' => SiteSetting::get('social_linkedin', ''),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_name' => ['required', 'string', 'max:255'],
            'site_tagline' => ['nullable', 'string', 'max:255'],
            'site_email' => ['nullable', 'email', 'max:255'],
            'site_phone' => ['nullable', 'string', 'max:50'],
            'site_address' => ['nullable', 'string', 'max:500'],
            'site_logo' => ['nullable', 'string', 'max:500'],
            'site_favicon' => ['nullable', 'string', 'max:500'],
            'footer_text' => ['nullable', 'string', 'max:1000'],
            'maintenance_mode' => ['boolean'],
            'maintenance_message' => ['nullable', 'string', 'max:500'],
            'social_facebook' => ['nullable', 'url', 'max:255'],
            'social_twitter' => ['nullable', 'url', 'max:255'],
            'social_instagram' => ['nullable', 'url', 'max:255'],
            'social_linkedin' => ['nullable', 'url', 'max:255'],
        ]);

        foreach ($validated as $key => $value) {
            $type = in_array($key, ['footer_text', 'maintenance_message', 'site_address']) ? 'textarea' : 'text';
            $type = in_array($key, ['maintenance_mode']) ? 'boolean' : $type;
            SiteSetting::set($key, $value, 'general', $type);
        }

        Cache::forget('site_settings');
        Cache::forget('site_settings_general');

        return back()->with('success', 'Site settings updated successfully.');
    }
}

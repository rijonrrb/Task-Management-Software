@extends('admin.layouts.app')

@section('title', 'Site Settings')
@section('page-title', 'Site Settings')
@section('page-subtitle', 'General site configuration and branding')

@section('content')
<form action="{{ route('admin.settings.update') }}" method="POST">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- General Settings --}}
        <div class="admin-card">
            <div class="p-5 border-b border-gray-100">
                <h3 class="text-sm font-semibold text-gray-700">General Information</h3>
            </div>
            <div class="p-5 space-y-4">
                <div>
                    <label class="admin-label">Site Name <span class="text-red-400">*</span></label>
                    <input type="text" name="site_name" value="{{ old('site_name', $settings['site_name']) }}" class="admin-input" required>
                    @error('site_name') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="admin-label">Tagline</label>
                    <input type="text" name="site_tagline" value="{{ old('site_tagline', $settings['site_tagline']) }}" class="admin-input" placeholder="A short description of your site">
                </div>
                <div>
                    <label class="admin-label">Contact Email</label>
                    <input type="email" name="site_email" value="{{ old('site_email', $settings['site_email']) }}" class="admin-input" placeholder="info@yoursite.com">
                </div>
                <div>
                    <label class="admin-label">Phone Number</label>
                    <input type="text" name="site_phone" value="{{ old('site_phone', $settings['site_phone']) }}" class="admin-input" placeholder="+1 234 567 890">
                </div>
                <div>
                    <label class="admin-label">Address</label>
                    <textarea name="site_address" class="admin-input" rows="2" placeholder="123 Street, City, Country">{{ old('site_address', $settings['site_address']) }}</textarea>
                </div>
            </div>
        </div>

        {{-- Branding --}}
        <div class="admin-card">
            <div class="p-5 border-b border-gray-100">
                <h3 class="text-sm font-semibold text-gray-700">Branding & Appearance</h3>
            </div>
            <div class="p-5 space-y-4">
                <div>
                    <label class="admin-label">Logo URL</label>
                    <input type="text" name="site_logo" value="{{ old('site_logo', $settings['site_logo']) }}" class="admin-input" placeholder="/images/logo.png or https://...">
                </div>
                <div>
                    <label class="admin-label">Favicon URL</label>
                    <input type="text" name="site_favicon" value="{{ old('site_favicon', $settings['site_favicon']) }}" class="admin-input" placeholder="/favicon.ico">
                </div>
                <div>
                    <label class="admin-label">Footer Text</label>
                    <textarea name="footer_text" class="admin-input" rows="3" placeholder="© 2025 Your Company. All rights reserved.">{{ old('footer_text', $settings['footer_text']) }}</textarea>
                </div>
            </div>
        </div>

        {{-- Social Media --}}
        <div class="admin-card">
            <div class="p-5 border-b border-gray-100">
                <h3 class="text-sm font-semibold text-gray-700">Social Media Links</h3>
            </div>
            <div class="p-5 space-y-4">
                <div>
                    <label class="admin-label">Facebook</label>
                    <input type="url" name="social_facebook" value="{{ old('social_facebook', $settings['social_facebook']) }}" class="admin-input" placeholder="https://facebook.com/yourpage">
                </div>
                <div>
                    <label class="admin-label">Twitter / X</label>
                    <input type="url" name="social_twitter" value="{{ old('social_twitter', $settings['social_twitter']) }}" class="admin-input" placeholder="https://twitter.com/yourhandle">
                </div>
                <div>
                    <label class="admin-label">Instagram</label>
                    <input type="url" name="social_instagram" value="{{ old('social_instagram', $settings['social_instagram']) }}" class="admin-input" placeholder="https://instagram.com/yourpage">
                </div>
                <div>
                    <label class="admin-label">LinkedIn</label>
                    <input type="url" name="social_linkedin" value="{{ old('social_linkedin', $settings['social_linkedin']) }}" class="admin-input" placeholder="https://linkedin.com/company/yourcompany">
                </div>
            </div>
        </div>

        {{-- Maintenance --}}
        <div class="admin-card">
            <div class="p-5 border-b border-gray-100">
                <h3 class="text-sm font-semibold text-gray-700">Maintenance Mode</h3>
            </div>
            <div class="p-5 space-y-4">
                <label class="flex items-center gap-2">
                    <input type="hidden" name="maintenance_mode" value="0">
                    <input type="checkbox" name="maintenance_mode" value="1" {{ old('maintenance_mode', $settings['maintenance_mode']) ? 'checked' : '' }} class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                    <span class="text-sm text-gray-600">Enable Maintenance Mode</span>
                </label>
                <p class="text-xs text-orange-400">⚠ When enabled, only admins can access the site.</p>
                <div>
                    <label class="admin-label">Maintenance Message</label>
                    <textarea name="maintenance_message" class="admin-input" rows="3">{{ old('maintenance_message', $settings['maintenance_message']) }}</textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-6">
        <button type="submit" class="btn-admin btn-admin-primary">Save Settings</button>
    </div>
</form>
@endsection

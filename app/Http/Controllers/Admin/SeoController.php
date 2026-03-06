<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SeoSetting;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SeoController extends Controller
{
    public function index()
    {
        $globalSeo = [
            'site_title' => SiteSetting::get('seo_site_title', ''),
            'site_description' => SiteSetting::get('seo_site_description', ''),
            'site_keywords' => SiteSetting::get('seo_site_keywords', ''),
            'google_analytics_id' => SiteSetting::get('google_analytics_id', ''),
            'google_verification' => SiteSetting::get('google_site_verification', ''),
            'bing_verification' => SiteSetting::get('bing_site_verification', ''),
            'robots_txt' => SiteSetting::get('robots_txt', "User-agent: *\nAllow: /\nSitemap: " . url('/sitemap.xml')),
            'og_image' => SiteSetting::get('seo_og_image', ''),
            'twitter_card' => SiteSetting::get('seo_twitter_card', 'summary_large_image'),
            'twitter_site' => SiteSetting::get('seo_twitter_site', ''),
            'canonical_base' => SiteSetting::get('seo_canonical_base', config('app.url')),
        ];

        $pageSeo = SeoSetting::all();

        return view('admin.seo.index', compact('globalSeo', 'pageSeo'));
    }

    public function updateGlobal(Request $request)
    {
        $validated = $request->validate([
            'site_title' => ['nullable', 'string', 'max:70'],
            'site_description' => ['nullable', 'string', 'max:160'],
            'site_keywords' => ['nullable', 'string', 'max:255'],
            'google_analytics_id' => ['nullable', 'string', 'max:50'],
            'google_verification' => ['nullable', 'string', 'max:100'],
            'bing_verification' => ['nullable', 'string', 'max:100'],
            'robots_txt' => ['nullable', 'string'],
            'og_image' => ['nullable', 'string', 'max:500'],
            'twitter_card' => ['nullable', 'in:summary,summary_large_image'],
            'twitter_site' => ['nullable', 'string', 'max:50'],
            'canonical_base' => ['nullable', 'url', 'max:255'],
        ]);

        SiteSetting::set('seo_site_title', $validated['site_title'], 'seo');
        SiteSetting::set('seo_site_description', $validated['site_description'], 'seo');
        SiteSetting::set('seo_site_keywords', $validated['site_keywords'], 'seo');
        SiteSetting::set('google_analytics_id', $validated['google_analytics_id'], 'seo');
        SiteSetting::set('google_site_verification', $validated['google_verification'], 'seo');
        SiteSetting::set('bing_site_verification', $validated['bing_verification'], 'seo');
        SiteSetting::set('robots_txt', $validated['robots_txt'], 'seo', 'textarea');
        SiteSetting::set('seo_og_image', $validated['og_image'], 'seo');
        SiteSetting::set('seo_twitter_card', $validated['twitter_card'], 'seo');
        SiteSetting::set('seo_twitter_site', $validated['twitter_site'], 'seo');
        SiteSetting::set('seo_canonical_base', $validated['canonical_base'], 'seo');

        Cache::forget('site_settings');
        Cache::forget('site_settings_seo');

        return back()->with('success', 'Global SEO settings updated.');
    }

    public function updatePage(Request $request, SeoSetting $seoSetting)
    {
        $validated = $request->validate([
            'meta_title' => ['nullable', 'string', 'max:70'],
            'meta_description' => ['nullable', 'string', 'max:160'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
            'og_title' => ['nullable', 'string', 'max:70'],
            'og_description' => ['nullable', 'string', 'max:200'],
            'og_image' => ['nullable', 'string', 'max:500'],
            'canonical_url' => ['nullable', 'url', 'max:255'],
            'structured_data' => ['nullable', 'string'],
        ]);

        $seoSetting->update($validated);

        return back()->with('success', 'Page SEO settings updated.');
    }

    public function storePage(Request $request)
    {
        $validated = $request->validate([
            'page_identifier' => ['required', 'string', 'max:100', 'unique:seo_settings'],
            'meta_title' => ['nullable', 'string', 'max:70'],
            'meta_description' => ['nullable', 'string', 'max:160'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
        ]);

        SeoSetting::create($validated);

        return back()->with('success', 'Page SEO entry created.');
    }
}

@extends('admin.layouts.app')

@section('title', 'SEO Settings')
@section('page-title', 'SEO Settings')
@section('page-subtitle', 'Search engine optimization configuration')

@section('content')
<div class="space-y-8">
    {{-- Global SEO Settings --}}
    <form action="{{ route('admin.seo.global') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="admin-card">
            <div class="p-5 border-b border-gray-100">
                <h3 class="text-sm font-semibold text-gray-700">Global SEO Settings</h3>
                <p class="text-xs text-gray-400 mt-1">These settings apply site-wide and affect all pages</p>
            </div>
            <div class="p-5 space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="admin-label">Site Title</label>
                        <input type="text" name="site_title" value="{{ old('site_title', $globalSeo['site_title']) }}" class="admin-input" maxlength="70" placeholder="Your Site Title">
                        <p class="text-xs text-gray-400 mt-1">Max 70 characters. Appears in browser tabs and search results.</p>
                        @error('site_title') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="admin-label">Canonical Base URL</label>
                        <input type="url" name="canonical_base" value="{{ old('canonical_base', $globalSeo['canonical_base']) }}" class="admin-input" placeholder="https://yourdomain.com">
                        @error('canonical_base') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="admin-label">Site Description</label>
                    <textarea name="site_description" class="admin-input" rows="2" maxlength="160" placeholder="A brief description of your site for search engines...">{{ old('site_description', $globalSeo['site_description']) }}</textarea>
                    <p class="text-xs text-gray-400 mt-1">Max 160 characters. Shown as snippet in Google search results.</p>
                    @error('site_description') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="admin-label">Keywords</label>
                    <input type="text" name="site_keywords" value="{{ old('site_keywords', $globalSeo['site_keywords']) }}" class="admin-input" placeholder="keyword1, keyword2, keyword3">
                    @error('site_keywords') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <hr class="border-gray-100">

                {{-- Verification --}}
                <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Search Engine Verification</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="admin-label">Google Analytics ID</label>
                        <input type="text" name="google_analytics_id" value="{{ old('google_analytics_id', $globalSeo['google_analytics_id']) }}" class="admin-input font-mono text-sm" placeholder="G-XXXXXXXXXX">
                    </div>
                    <div>
                        <label class="admin-label">Google Verification Code</label>
                        <input type="text" name="google_verification" value="{{ old('google_verification', $globalSeo['google_verification']) }}" class="admin-input font-mono text-sm" placeholder="google-site-verification code">
                    </div>
                    <div>
                        <label class="admin-label">Bing Verification Code</label>
                        <input type="text" name="bing_verification" value="{{ old('bing_verification', $globalSeo['bing_verification']) }}" class="admin-input font-mono text-sm" placeholder="Bing webmaster verification">
                    </div>
                </div>

                <hr class="border-gray-100">

                {{-- Social / Open Graph --}}
                <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Social Media / Open Graph</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="admin-label">Default OG Image URL</label>
                        <input type="text" name="og_image" value="{{ old('og_image', $globalSeo['og_image']) }}" class="admin-input" placeholder="https://yourdomain.com/og-image.jpg">
                    </div>
                    <div>
                        <label class="admin-label">Twitter Card Type</label>
                        <select name="twitter_card" class="admin-input">
                            <option value="summary" {{ old('twitter_card', $globalSeo['twitter_card']) === 'summary' ? 'selected' : '' }}>Summary</option>
                            <option value="summary_large_image" {{ old('twitter_card', $globalSeo['twitter_card']) === 'summary_large_image' ? 'selected' : '' }}>Summary Large Image</option>
                        </select>
                    </div>
                    <div>
                        <label class="admin-label">Twitter @username</label>
                        <input type="text" name="twitter_site" value="{{ old('twitter_site', $globalSeo['twitter_site']) }}" class="admin-input" placeholder="@yoursite">
                    </div>
                </div>

                <hr class="border-gray-100">

                {{-- Robots.txt --}}
                <div>
                    <label class="admin-label">Robots.txt Content</label>
                    <textarea name="robots_txt" class="admin-input font-mono text-sm" rows="6">{{ old('robots_txt', $globalSeo['robots_txt']) }}</textarea>
                    <p class="text-xs text-gray-400 mt-1">Controls which pages search engines can crawl. Be careful with this.</p>
                </div>
            </div>
            <div class="p-5 border-t border-gray-100 bg-gray-50/50">
                <button type="submit" class="btn-admin btn-admin-primary">Save SEO Settings</button>
            </div>
        </div>
    </form>

    {{-- Per-Page SEO --}}
    <div class="admin-card">
        <div class="p-5 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-semibold text-gray-700">Page-Level SEO</h3>
                <p class="text-xs text-gray-400 mt-1">Override SEO for specific pages</p>
            </div>
            <button onclick="document.getElementById('add-page-seo').classList.toggle('hidden')" class="btn-admin btn-admin-outline btn-admin-sm">+ Add Page</button>
        </div>

        {{-- Add New Page SEO --}}
        <div id="add-page-seo" class="hidden p-5 border-b border-gray-100 bg-indigo-50/30">
            <form action="{{ route('admin.seo.page.store') }}" method="POST" class="space-y-3">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                    <div>
                        <input type="text" name="page_identifier" class="admin-input text-sm" placeholder="Page Identifier (e.g. home, about)" required>
                    </div>
                    <div>
                        <input type="text" name="meta_title" class="admin-input text-sm" placeholder="Meta Title" maxlength="70">
                    </div>
                    <div>
                        <input type="text" name="meta_description" class="admin-input text-sm" placeholder="Meta Description" maxlength="160">
                    </div>
                    <div>
                        <button type="submit" class="btn-admin btn-admin-primary btn-admin-sm w-full">Add</button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Existing Page SEO --}}
        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Page</th>
                        <th>Meta Title</th>
                        <th>Meta Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pageSeo as $seo)
                    <tr>
                        <td class="font-mono text-sm font-medium text-gray-700">{{ $seo->page_identifier }}</td>
                        <td class="text-sm text-gray-500">{{ Str::limit($seo->meta_title, 40) ?: '—' }}</td>
                        <td class="text-sm text-gray-400">{{ Str::limit($seo->meta_description, 50) ?: '—' }}</td>
                        <td>
                            <button onclick="togglePageEdit({{ $seo->id }})" class="btn-admin btn-admin-primary btn-admin-sm">Edit</button>
                        </td>
                    </tr>
                    <tr id="page-edit-{{ $seo->id }}" class="hidden">
                        <td colspan="4" class="bg-gray-50/50">
                            <form action="{{ route('admin.seo.page.update', $seo) }}" method="POST" class="p-4 space-y-3">
                                @csrf @method('PUT')
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <div>
                                        <label class="admin-label text-xs">Meta Title</label>
                                        <input type="text" name="meta_title" value="{{ $seo->meta_title }}" class="admin-input text-sm" maxlength="70">
                                    </div>
                                    <div>
                                        <label class="admin-label text-xs">Meta Keywords</label>
                                        <input type="text" name="meta_keywords" value="{{ $seo->meta_keywords }}" class="admin-input text-sm">
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="admin-label text-xs">Meta Description</label>
                                        <textarea name="meta_description" class="admin-input text-sm" rows="2" maxlength="160">{{ $seo->meta_description }}</textarea>
                                    </div>
                                    <div>
                                        <label class="admin-label text-xs">OG Title</label>
                                        <input type="text" name="og_title" value="{{ $seo->og_title }}" class="admin-input text-sm">
                                    </div>
                                    <div>
                                        <label class="admin-label text-xs">OG Image URL</label>
                                        <input type="text" name="og_image" value="{{ $seo->og_image }}" class="admin-input text-sm">
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="admin-label text-xs">OG Description</label>
                                        <textarea name="og_description" class="admin-input text-sm" rows="2">{{ $seo->og_description }}</textarea>
                                    </div>
                                    <div>
                                        <label class="admin-label text-xs">Canonical URL</label>
                                        <input type="url" name="canonical_url" value="{{ $seo->canonical_url }}" class="admin-input text-sm">
                                    </div>
                                    <div>
                                        <label class="admin-label text-xs">Structured Data (JSON-LD)</label>
                                        <textarea name="structured_data" class="admin-input text-sm font-mono" rows="3">{{ $seo->structured_data }}</textarea>
                                    </div>
                                </div>
                                <button type="submit" class="btn-admin btn-admin-primary btn-admin-sm">Update</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-gray-400 py-8">No page-level SEO entries yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
function togglePageEdit(id) {
    document.getElementById('page-edit-' + id).classList.toggle('hidden');
}
</script>
@endpush
@endsection

@extends('admin.layouts.app')

@section('title', isset($page) ? 'Edit Page' : 'Create Page')
@section('page-title', isset($page) ? 'Edit Page' : 'Create Page')
@section('page-subtitle', isset($page) ? $page->title : 'Add new content page')

@section('content')
<form action="{{ isset($page) ? route('admin.pages.update', $page) : route('admin.pages.store') }}" method="POST">
    @csrf
    @if(isset($page)) @method('PUT') @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Title --}}
            <div class="admin-card p-5">
                <label class="admin-label">Page Title <span class="text-red-400">*</span></label>
                <input type="text" name="title" value="{{ old('title', $page->title ?? '') }}" class="admin-input @error('title') border-red-400 @enderror" required placeholder="Enter page title...">
                @error('title') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Content Editor --}}
            <div class="admin-card p-5">
                <label class="admin-label">Page Content <span class="text-red-400">*</span></label>
                <textarea name="content" id="page-editor" class="admin-input" rows="20">{{ old('content', $page->content ?? '') }}</textarea>
                @error('content') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-5">
            {{-- Publish Settings --}}
            <div class="admin-card p-5">
                <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4">Publish</h4>

                <div class="space-y-3">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="is_published" value="1" {{ old('is_published', $page->is_published ?? false) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <span class="text-sm text-gray-600">Published</span>
                    </label>

                    <div>
                        <label class="admin-label text-xs">Custom Slug</label>
                        <input type="text" name="slug" value="{{ old('slug', $page->slug ?? '') }}" class="admin-input text-sm" placeholder="Auto-generated from title">
                        <p class="text-xs text-gray-400 mt-1">Leave empty to auto-generate</p>
                    </div>
                </div>
            </div>

            {{-- Menu Settings --}}
            <div class="admin-card p-5">
                <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4">Menu</h4>
                <div class="space-y-3">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="show_in_menu" value="1" {{ old('show_in_menu', $page->show_in_menu ?? false) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <span class="text-sm text-gray-600">Show in Navigation</span>
                    </label>
                    <div>
                        <label class="admin-label text-xs">Menu Position</label>
                        <select name="menu_position" class="admin-input text-sm">
                            <option value="footer" {{ old('menu_position', $page->menu_position ?? 'footer') === 'footer' ? 'selected' : '' }}>Footer</option>
                            <option value="header" {{ old('menu_position', $page->menu_position ?? '') === 'header' ? 'selected' : '' }}>Header</option>
                            <option value="both" {{ old('menu_position', $page->menu_position ?? '') === 'both' ? 'selected' : '' }}>Both</option>
                        </select>
                    </div>
                    <div>
                        <label class="admin-label text-xs">Sort Order</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', $page->sort_order ?? 0) }}" class="admin-input text-sm" min="0">
                    </div>
                </div>
            </div>

            {{-- SEO Meta --}}
            <div class="admin-card p-5">
                <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4">SEO</h4>
                <div class="space-y-3">
                    <div>
                        <label class="admin-label text-xs">Meta Title</label>
                        <input type="text" name="meta_title" value="{{ old('meta_title', $page->meta_title ?? '') }}" class="admin-input text-sm" placeholder="Page title for search engines" maxlength="70">
                        <p class="text-xs text-gray-400 mt-1">Max 70 characters</p>
                    </div>
                    <div>
                        <label class="admin-label text-xs">Meta Description</label>
                        <textarea name="meta_description" class="admin-input text-sm" rows="3" placeholder="Brief page description for search results" maxlength="160">{{ old('meta_description', $page->meta_description ?? '') }}</textarea>
                        <p class="text-xs text-gray-400 mt-1">Max 160 characters</p>
                    </div>
                    <div>
                        <label class="admin-label text-xs">Meta Keywords</label>
                        <input type="text" name="meta_keywords" value="{{ old('meta_keywords', $page->meta_keywords ?? '') }}" class="admin-input text-sm" placeholder="keyword1, keyword2, keyword3">
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="space-y-2">
                <button type="submit" class="btn-admin btn-admin-primary w-full">
                    {{ isset($page) ? 'Update Page' : 'Create Page' }}
                </button>
                <a href="{{ route('admin.pages.index') }}" class="btn-admin btn-admin-outline w-full text-center block">Cancel</a>
            </div>
        </div>
    </div>
</form>

@push('styles')
{{-- TinyMCE Editor --}}
<style>
    .tox-tinymce { border-radius: 0.5rem !important; border-color: #e5e7eb !important; }
    .tox .tox-toolbar__primary { background-color: #f9fafb !important; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof tinymce !== 'undefined') {
        tinymce.init({
            selector: '#page-editor',
            height: 500,
            menubar: 'file edit view insert format tools table',
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'media', 'table', 'help', 'wordcount',
                'emoticons', 'codesample', 'directionality'
            ],
            toolbar: 'undo redo | blocks | bold italic underline strikethrough | forecolor backcolor | ' +
                     'alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | ' +
                     'link image media table | codesample emoticons | removeformat code fullscreen help',
            content_style: 'body { font-family: Inter, sans-serif; font-size: 14px; line-height: 1.6; color: #374151; padding: 16px; }',
            branding: false,
            promotion: false,
            skin: 'oxide',
            setup: function(editor) {
                editor.on('change', function() {
                    editor.save();
                });
            }
        });
    }
});
</script>
@endpush
@endsection

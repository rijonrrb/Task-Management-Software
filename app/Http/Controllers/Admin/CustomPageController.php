<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomPage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class CustomPageController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = CustomPage::query();

            return DataTables::eloquent($query)
                ->addColumn('status_html', function (CustomPage $page) {
                    $class = $page->is_published ? 'badge-success' : 'badge-secondary';
                    $label = $page->is_published ? 'Published' : 'Draft';
                    return '<span class="badge ' . $class . '">' . $label . '</span>';
                })
                ->addColumn('menu_html', function (CustomPage $page) {
                    if ($page->show_in_menu) {
                        return '<span class="text-green-600 text-xs font-medium">Yes (' . e($page->menu_position) . ')</span>';
                    }
                    return '<span class="text-gray-400 text-xs">No</span>';
                })
                ->editColumn('created_at', function (CustomPage $page) {
                    return $page->created_at->format('M d, Y');
                })
                ->addColumn('actions', function (CustomPage $page) {
                    $previewUrl = url('/page/' . $page->slug);
                    $editUrl    = route('admin.pages.edit', $page);
                    $deleteUrl  = route('admin.pages.destroy', $page);
                    $csrf       = csrf_token();
                    $formId     = 'del-page-' . $page->id;
                    return '<div class="flex items-center gap-1">'
                        . '<a href="' . $previewUrl . '" target="_blank" class="btn-admin btn-admin-outline btn-admin-sm" title="Preview">'
                        . '<svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>'
                        . '</a>'
                        . '<a href="' . $editUrl . '" class="btn-admin btn-admin-primary btn-admin-sm">Edit</a>'
                        . '<form action="' . $deleteUrl . '" method="POST" id="' . $formId . '" class="inline">'
                        . '<input type="hidden" name="_token" value="' . $csrf . '">'
                        . '<input type="hidden" name="_method" value="DELETE">'
                        . '<button type="button" onclick="confirmDelete(\'' . $formId . '\', \'Delete this page permanently?\')" class="btn-admin btn-admin-danger btn-admin-sm">Delete</button>'
                        . '</form></div>';
                })
                ->rawColumns(['status_html', 'menu_html', 'actions'])
                ->toJson();
        }

        return view('admin.pages.index');
    }


    public function create()
    {
        return view('admin.pages.form', ['page' => null]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:custom_pages'],
            'content' => ['nullable', 'string'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
            'is_published' => ['boolean'],
            'show_in_menu' => ['boolean'],
            'menu_position' => ['in:header,footer,both'],
            'sort_order' => ['integer', 'min:0'],
        ]);

        $validated['slug'] = $validated['slug'] ?: Str::slug($validated['title']);
        $validated['created_by_admin_id'] = auth('admin')->id();

        CustomPage::create($validated);

        return redirect()->route('admin.pages.index')->with('success', 'Page created successfully.');
    }

    public function edit(CustomPage $page)
    {
        return view('admin.pages.form', compact('page'));
    }

    public function update(Request $request, CustomPage $page)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:custom_pages,slug,' . $page->id],
            'content' => ['nullable', 'string'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
            'is_published' => ['boolean'],
            'show_in_menu' => ['boolean'],
            'menu_position' => ['in:header,footer,both'],
            'sort_order' => ['integer', 'min:0'],
        ]);

        $validated['slug'] = $validated['slug'] ?: Str::slug($validated['title']);

        $page->update($validated);

        return redirect()->route('admin.pages.index')->with('success', 'Page updated successfully.');
    }

    public function destroy(CustomPage $page)
    {
        $page->delete();
        return redirect()->route('admin.pages.index')->with('success', 'Page deleted successfully.');
    }
}

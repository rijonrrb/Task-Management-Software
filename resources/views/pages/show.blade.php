@extends('layouts.app')
@section('title', $page->meta_title ?: $page->title)

@if($page->meta_description)
@section('meta_description', $page->meta_description)
@endif
@if($page->meta_keywords)
@section('meta_keywords', $page->meta_keywords)
@endif

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6">
    <article>
        <h1 class="text-3xl font-bold text-slate-800 dark:text-white mb-6">{{ $page->title }}</h1>
        <div class="prose dark:prose-invert prose-indigo max-w-none text-slate-600 dark:text-slate-300">
            {!! $page->content !!}
        </div>
    </article>
</div>
@endsection

@extends('layouts.front')
@section('title', $sort->name)
@section('content')
    <div class="posts">
        @foreach($articles as $article)
            <div class="post">
                <h3><a href="/article/{{$article->id}}">{{$article->title}}</a></h3>
                <h5>分类：{{$sort->name}}， 发布于 {{str_limit($article->created_at,10,'')}}，<a href="/article/{{$article->id}}#comments">评论({{count($article->comments)}})</a>，<a
                            href="/article/{{$article->id}}">浏览({{$article->views}})</a></h5>
                <hr>
                <div class="content">
                    {!! $article->content !!}
                </div>
            </div>
        @endforeach
    </div>
    @if ($articles->hasPages())
        <ul class="pagination">
            {{-- Previous Page Link --}}
            @if ($articles->onFirstPage())
                <li class="disabled"><span>&laquo;</span></li>
            @else
                <li><a href="{{ $articles->previousPageUrl() }}" rel="prev">&laquo;</a></li>
            @endif

            {{-- Next Page Link --}}
            @if ($articles->hasMorePages())
                <li><a href="{{ $articles->nextPageUrl() }}" rel="next">&raquo;</a></li>
            @else
                <li class="disabled"><span>&raquo;</span></li>
            @endif
        </ul>
    @endif
@endsection
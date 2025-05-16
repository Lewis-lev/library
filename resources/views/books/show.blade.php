
@extends('layouts.guest')

@section('content')
<style>
    body {
        background: #f4f4ee !important;
    }
    .ol-book-hero {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.09);
        padding: 2rem;
        margin: 40px auto;
        max-width: 880px;
        display: flex;
        flex-wrap: wrap;
        gap: 30px;
        align-items: flex-start;
    }
    .ol-book-cover {
        width: 220px;
        flex-shrink: 0;
        border-radius: 9px;
        overflow: hidden;
        box-shadow: 0 4px 24px rgba(44,45,63,0.14);
        background: #dedede;
        aspect-ratio: 3/4;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .ol-book-meta {
        flex: 1 1 320px;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        min-width: 220px;
        max-width: 465px;
    }
    .ol-title {
        font-size: 2.2rem;
        font-weight: bold;
        margin-bottom: 0.35rem;
        color: #2c2633;
    }
    .ol-author {
        font-size: 1.18rem;
        color: #3951b2;
        margin-bottom: 0.6rem;
        font-weight: 500;
    }
    .ol-meta-table {
        background: #f9f8f3;
        border-radius: 9px;
        margin-top: 1rem;
        margin-bottom: 1.35rem;
        border: 1px solid #f0eee7;
        font-size: 1rem;
        color: #595863;
        line-height: 1.54;
        width: 110%;
        box-shadow: 0 1px 2px rgba(0,0,0,0.03);
    }
    .ol-meta-table tr th {
        margin-left: 2rem;
        padding: 1rem 0 .5rem 2rem;
        font-weight: 600;
        width: 43%;
        vertical-align: top;
        background: #f9f8f3;
        border: none;
    }
    .ol-meta-table tr td {
        padding: 0.65em 0 0.65em 0;
        vertical-align: top;
        background: #f9f8f3;
        border: none;
    }
    .ol-desc-block {
        background: #f9fafc;
        border-left: 4px solid #3951b2;
        padding: 1.1em 1.5em;
        border-radius: 1rem;
        margin-top: 1.1em;
        font-size: 1.07em;
        color: #434249;
        min-height: 85px;
        box-shadow: 0 1px 3px rgba(44,45,63,0.07);
    }
    .ol-tag {
        background: #e6f2ff;
        color: #233970;
        border-radius: .5em;
        font-size: .96em;
        display: inline-block;
        padding: 0.23em 0.8em 0.23em 0.75em;
        margin-right: 0.38em;
        margin-bottom: 0.27em;
        border: 1px solid #d1d7e0;
        font-weight: 600;
        letter-spacing: 0.01em;
        box-shadow: 0 1px 2px rgba(34,69,121,.05);
    }
    @media (max-width: 992px) {
        .ol-book-hero {
            flex-direction: column;
            align-items: center;
            padding: 2.3rem 1.1rem;
        }
        .ol-book-cover {
            margin-bottom: 1.3rem;
        }
        .ol-book-meta {
            max-width: 100%;
        }
    }
</style>

<div class="container-xxl" style="min-height:90vh">

    <a href="{{ route('books.index') }}" class="btn btn-primary mt-4 mb-2" style="font-weight:500;">
        <i class="fa fa-arrow-left"></i> Browse more books!
    </a>
    <div class="ol-book-hero">
        <div class="ol-book-cover">
            @if ($book->image)
                <img src="{{ asset('https://pub-94f23dc765bc4b62a5ef536b35ffa982.r2.dev/img/book_images/' . $book->image) }}"
                     alt="{{ $book->title }}" style="width: 100%; height: auto; object-fit: cover;">
            @else
                <img src="{{ asset('default-book.jpg') }}"
                     alt="No cover" style="width: 100%; height: auto;">
            @endif
        </div>
        <div class="ol-book-meta">
            <div class="ol-title">{{ $book->title }}</div>
            <div class="ol-author">
                @if(!empty($book->author))
                    by {{ $book->author }}
                @endif
            </div>
            <table class="ol-meta-table table mb-2">
                <tbody>
                    <tr>
                        <th>Publisher</th>
                        <td>{{ $book->publisher ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Stock</th>
                        <td>{{ $book->quantity ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Code</th>
                        <td>{{ $book->code ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Genres</th>
                        <td>
                            @if ($book->genres && $book->genres->count())
                                @foreach($book->genres as $genre)
                                    <span class="ol-tag">{{ $genre->name }}</span>
                                @endforeach
                            @else
                                <span class="text-muted">None</span>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="ol-desc-block">
                <strong>Description:</strong><br>
                {!! nl2br(e($book->description ?? 'No description provided.')) !!}
            </div>
        </div>
    </div>
</div>
@endsection

<!DOCTYPE html>
<html>

<head>
    <title>Daily RSS Feed Summary</title>
</head>

<body>
    <h1>Daily RSS Feed Summary</h1>
    <ul>
        @foreach ($articles as $article)
            <li>
                <h2>{{ $article->title }}</h2>
                <p>{{ $article->author }}</p>
                <p>{{ Str::limit(strip_tags($article->description), 360) }}</p>
                <a href="{{ route('articles.read', $article->id) }}">Read More</a>
            </li>
        @endforeach
    </ul>
</body>

</html>

<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0"
     xmlns:content="http://purl.org/rss/1.0/modules/content/"
     xmlns:wfw="http://wellformedweb.org/CommentAPI/"
     xmlns:dc="http://purl.org/dc/elements/1.1/"
     xmlns:atom="http://www.w3.org/2005/Atom"
     xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
     xmlns:slash="http://purl.org/rss/1.0/modules/slash/"
     xmlns:media="http://search.yahoo.com/mrss/">
    <channel>
        <title>{{ config('app.name', 'Look at History') }}</title>
        <link>{{ url('/') }}</link>
        <description>Blog sejarah dunia ringkas & terpercaya. Temukan artikel peradaban kuno, perang dunia, tokoh sejarah, dan peristiwa penting masa lalu.</description>
        <language>id-ID</language>
        <atom:link href="{{ route('feed') }}" rel="self" type="application/rss+xml" />
        <pubDate>{{ now()->format('D, d M Y H:i:s O') }}</pubDate>
        <lastBuildDate>{{ $artikels->first()?->created_at->format('D, d M Y H:i:s O') ?? now()->format('D, d M Y H:i:s O') }}</lastBuildDate>
        <sy:updatePeriod>daily</sy:updatePeriod>
        <sy:updateFrequency>1</sy:updateFrequency>

        @foreach ($artikels as $artikel)
            <item>
                <title>{{ $artikel->judul }}</title>
                <link>{{ route('artikel.show', $artikel) }}</link>
                <guid isPermaLink="true">{{ route('artikel.show', $artikel) }}</guid>
                <description>{{ $artikel->ringkasan ?? Str::limit(strip_tags($artikel->konten), 200) }}</description>
                <content:encoded><![CDATA[{{ $artikel->konten }}]]></content:encoded>
                <pubDate>{{ $artikel->created_at->format('D, d M Y H:i:s O') }}</pubDate>
                <dc:creator>{{ $artikel->author->name ?? config('app.name', 'Look at History') }}</dc:creator>
                <category>{{ $artikel->kategori->nama ?? 'Umum' }}</category>
                @if ($artikel->gambar)
                    <media:content url="{{ asset('storage/' . $artikel->gambar) }}" type="image/jpeg" />
                @endif
            </item>
        @endforeach
    </channel>
</rss>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $longtitle ?? 'Магазин' }}</title>
<meta name="description" content="{{ $description ?? '' }}">
<base href="@config('site_url')">
<link rel="icon" href="resources/favicon/buycolor-16.png" sizes="16x16" type="image/png">
<link rel="icon" href="resources/favicon/buycolor-32.png" sizes="32x32" type="image/png">
<link rel="icon" href="resources/favicon/buycolor-96.png" sizes="96x96" type="image/png">
<link rel="stylesheet" href="resources/fontawesome-free-7.2.0-web/css/all.min.css">
<link rel="stylesheet" href="resources/css/main.css">
<link rel="stylesheet" href="assets/plugins/aesearch/aesearch.css">
@if ($searchable ?? true)
<meta name="robots" content="index, follow">
@else
<meta name="robots" content="noindex, nofollow">
@endif
<link rel="canonical" href="{{ urlProcessor::makeUrl($id ?? 1,'','','full') }}">
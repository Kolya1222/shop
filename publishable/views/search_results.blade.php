@extends('layout.app')

@section('styles')
@endsection

@section('content')
    <div class="container">
        {!! evo()->runSnippet('evoSearch', [
            'display' => '16',
            'paginate' => 'pages',
            'statTpl' => '
                <div class="srch_res_info">По запросу <b>[+stat_request+]</b>
                найдено <b>[+stat_total+]</b> документов.</div>',
            'noResult' => '@CODE:
                <div class="srch_res_info">По запросу <u>[+stat_request+]</u>
                ничего не найдено. Смягчите условия поиска</div>',
            'ownerTpl' => '@CODE:
                <div class="srch_res">[+dl.wrap+] </div>',
            'tpl' => '@CODE:
                <div class="srch_res_one">
                    <a href="[+url+]">[+title+]</a>
                    <div class="srch_ext">[+extract+]</div>
                </div>',
            'TplNextP' => '',
            'TplPrevP' => '',
            'TplPage' => '@CODE:
                <li><a href="[+link+]">[+num+]</a></li>',
            'TplCurrentPage' => '@CODE:
                <li class="active"><a href="[+link+]">[+num+]</a></li>',
            'TplWrapPaginate' => '@CODE:
                <div class="pagination"><ul>[+wrap+]</ul></div>',
        ]) !!}
    </div>
@endsection

@section('scripts')
@endsection

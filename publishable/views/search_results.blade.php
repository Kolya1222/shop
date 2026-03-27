@extends('layout.app')

@section('styles')
    <style>
        .srch_res_info {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border: 1px solid var(--border-light, #e2e8f0);
            border-radius: 16px;
            padding: 20px 24px;
            margin: 30px 0;
            font-size: 1.1rem;
            color: var(--graphite, #334155);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
        }

        .srch_res_info b {
            color: var(--fresh-green, #2e7d5e);
            font-weight: 600;
        }

        .srch_res {
            margin: 30px 0 50px;
        }

        .srch_res_one {
            background: var(--white, #ffffff);
            border: 1px solid var(--border-light, #e2e8f0);
            border-radius: 24px;
            padding: 24px 28px;
            margin-bottom: 16px;
            transition: all 0.2s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
        }

        .srch_res_one:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.08);
            border-color: var(--fresh-green, #2e7d5e);
        }

        .srch_res_one a {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--deep-green, #1a4b3c);
            text-decoration: none;
            display: inline-block;
            margin-bottom: 12px;
            transition: color 0.2s;
        }

        .srch_res_one a:hover {
            color: var(--fresh-green, #2e7d5e);
            text-decoration: underline;
        }

        .srch_ext {
            color: var(--light-graphite, #64748b);
            line-height: 1.6;
            font-size: 0.95rem;
            padding: 12px 0 4px;
            border-top: 1px dashed var(--border-light, #e2e8f0);
            margin-top: 8px;
        }

        .srch_ext strong,
        .srch_ext b {
            background: rgba(46, 125, 94, 0.15);
            color: var(--deep-green, #1a4b3c);
            font-weight: 600;
            padding: 2px 4px;
            border-radius: 4px;
        }

        .pagination {
            margin: 40px 0 20px;
        }

        .pagination ul {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 8px;
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .pagination li {
            display: inline-block;
        }

        .pagination li a {
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 42px;
            height: 42px;
            padding: 0 12px;
            background: var(--white, #ffffff);
            border: 1px solid var(--border-light, #e2e8f0);
            border-radius: 30px;
            color: var(--graphite, #334155);
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s;
        }

        .pagination li a:hover {
            background: var(--border-light, #e2e8f0);
            border-color: var(--fresh-green, #2e7d5e);
            color: var(--deep-green, #1a4b3c);
        }

        .pagination li.active a {
            background: var(--deep-green, #1a4b3c);
            border-color: var(--deep-green, #1a4b3c);
            color: white;
            cursor: default;
            pointer-events: none;
        }

        .pagination li:first-child a,
        .pagination li:last-child a {
            padding: 0 18px;
        }

        @media (max-width: 768px) {
            .srch_res_info {
                padding: 16px 20px;
                font-size: 1rem;
            }

            .srch_res_one {
                padding: 20px;
            }

            .srch_res_one a {
                font-size: 1.2rem;
            }

            .pagination li a {
                min-width: 38px;
                height: 38px;
                font-size: 0.9rem;
            }
        }

        @media (max-width: 480px) {
            .srch_res_one {
                padding: 16px;
            }

            .srch_res_one a {
                font-size: 1.1rem;
            }

            .pagination ul {
                gap: 5px;
            }

            .pagination li a {
                min-width: 36px;
                height: 36px;
                padding: 0 8px;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container">
        {!! evo()->runSnippet('evoSearch', [
            'display'   => '16',
            'paginate'  => 'pages',
            'statTpl'   => '
                <div class="srch_res_info">По запросу <b>[+stat_request+]</b>
                найдено <b>[+stat_total+]</b> документов.</div>',
            'noResult'  => '@CODE:
                <div class="srch_res_info">По запросу <u>[+stat_request+]</u>
                ничего не найдено. Смягчите условия поиска</div>',
            'ownerTpl'  => '@CODE:
                <div class="srch_res">[+dl.wrap+] </div>',
            'tpl'       => '@CODE:
                <div class="srch_res_one">
                    <a href="[+url+]">[+title+]</a>
                    <div class="srch_ext">[+extract+]</div>
                </div>',
            'TplNextP'  => '',
            'TplPrevP'  => '',
            'TplPage'   => '@CODE:
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

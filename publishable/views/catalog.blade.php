@extends('layout.app')

@section('styles')
    <style>
        .catalog-layout {
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 30px;
            margin: 40px 0;
        }

        .filter-sidebar {
            background: var(--white);
            border-radius: 32px;
            border: 1px solid var(--border-light);
            padding: 24px;
            height: fit-content;
            position: sticky;
            top: 100px;
        }

        .filter-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 1px solid var(--border-light);
        }

        .filter-header h3 {
            font-size: 1.3rem;
            color: var(--deep-green);
        }

        .filter-clear {
            color: var(--fresh-green);
            font-size: 0.9rem;
            cursor: pointer;
            text-decoration: none;
            font-weight: 500;
        }

        .filter-clear:hover {
            text-decoration: underline;
        }

        .filter-section {
            margin-bottom: 28px;
        }

        .filter-section-title {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
            font-weight: 600;
            color: var(--graphite);
            cursor: pointer;
        }

        .filter-section-title i {
            transition: transform 0.2s;
            color: var(--fresh-green);
        }

        .filter-section.active .filter-section-title i {
            transform: rotate(180deg);
        }

        .filter-options {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .filter-option {
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--light-graphite);
            font-size: 0.95rem;
            cursor: pointer;
        }

        .filter-option input[type="checkbox"] {
            width: 18px;
            height: 18px;
            border-radius: 4px;
            border: 2px solid var(--border-light);
            accent-color: var(--fresh-green);
            cursor: pointer;
            transition: all 0.2s;
        }

        .filter-option input[type="checkbox"]:hover {
            border-color: var(--fresh-green);
        }

        .filter-option input[type="checkbox"]:checked {
            background-color: var(--fresh-green);
            border-color: var(--fresh-green);
        }

        .filter-option .count {
            margin-left: auto;
            color: #94a3b8;
            font-size: 0.85rem;
        }

        .filter-range {
            padding: 8px 0;
        }

        .filter-range-inputs {
            display: flex;
            gap: 12px;
            margin-top: 12px;
            flex-wrap: wrap;
        }

        .filter-range-input {
            flex: 1;
            padding: 10px 12px;
            border: 1px solid var(--border-light);
            border-radius: 30px;
            font-size: 0.9rem;
            outline: none;
            transition: border-color 0.2s;
        }

        .filter-range-input:focus {
            border-color: var(--fresh-green);
        }

        .filter-apply-btn {
            width: 100%;
            background: var(--deep-green);
            color: white;
            border: none;
            padding: 14px;
            border-radius: 40px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.2s;
            margin-top: 16px;
        }

        .filter-apply-btn:hover {
            background: var(--fresh-green);
        }

        .filter-apply-btn:disabled,
        .filter-apply-btn.disabled {
            background: var(--border-light);
            cursor: not-allowed;
            opacity: 0.7;
            pointer-events: none;
        }

        .catalog-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 16px;
        }

        .catalog-title h1 {
            font-size: 2rem;
            color: var(--deep-green);
        }

        .catalog-title p {
            color: var(--light-graphite);
            margin-top: 4px;
        }

        .catalog-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
            margin-bottom: 50px;
        }

        .filter-option input[type="radio"] {
            width: 18px;
            height: 18px;
            margin: 0;
            accent-color: var(--fresh-green);
            cursor: pointer;
        }

        .filter-option input[type="radio"]:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .filter-multiselect {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid var(--border-light);
            border-radius: 20px;
            font-family: 'Space Grotesk', sans-serif;
            font-size: 0.95rem;
            color: var(--graphite);
            background: var(--white);
            cursor: pointer;
            outline: none;
            min-height: 120px;
        }

        .filter-multiselect option {
            padding: 10px 12px;
            border-bottom: 1px solid var(--border-light);
            transition: background 0.2s;
        }

        .filter-multiselect option:checked {
            background: var(--fresh-green) linear-gradient(0deg, var(--fresh-green) 0%, var(--fresh-green) 100%);
            color: white;
        }

        .filter-multiselect option:hover {
            background: var(--border-light);
        }

        .filter-multiselect:focus {
            border-color: var(--fresh-green);
            box-shadow: 0 0 0 3px rgba(74, 144, 130, 0.1);
        }

        .filter-multiselect:disabled,
        .filter-multiselect.disabled {
            background-color: #f5f5f5;
            border-color: #e0e0e0;
            color: #999;
            cursor: not-allowed;
            opacity: 0.7;
        }

        .filter-multiselect-checkboxes {
            border: 1px solid var(--border-light);
            border-radius: 20px;
            padding: 8px;
            background: var(--white);
            max-height: 200px;
            overflow-y: auto;
        }

        .filter-multiselect-checkboxes label {
            display: flex;
            align-items: center;
            padding: 10px 12px;
            gap: 10px;
            cursor: pointer;
            color: var(--graphite);
            border-radius: 12px;
            transition: background 0.2s;
        }

        .filter-multiselect-checkboxes label:hover {
            background: var(--border-light);
        }

        .filter-multiselect-checkboxes label.disabled {
            opacity: 0.5;
            cursor: not-allowed;
            pointer-events: none;
        }

        .filter-multiselect-checkboxes input[type="checkbox"] {
            width: 18px;
            height: 18px;
            border-radius: 4px;
            border: 2px solid var(--border-light);
            accent-color: var(--fresh-green);
            cursor: pointer;
        }

        .filter-multiselect-checkboxes input[type="checkbox"]:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .filter-multiselect-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 12px;
        }

        .filter-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            background: var(--border-light);
            border-radius: 30px;
            font-size: 0.9rem;
            color: var(--graphite);
        }

        .filter-tag i {
            cursor: pointer;
            color: var(--light-graphite);
            transition: color 0.2s;
            font-size: 14px;
        }

        .filter-tag i:hover {
            color: #ef4444;
        }

        .filter-tag.disabled {
            opacity: 0.5;
            pointer-events: none;
        }

        .filter-mobile-btn {
            display: none;
            width: 100%;
            padding: 14px;
            background: var(--deep-green);
            color: white;
            border: none;
            border-radius: 40px;
            font-weight: 600;
            margin-bottom: 20px;
            align-items: center;
            justify-content: center;
            gap: 10px;
            cursor: pointer;
        }

        .filter-mobile-btn:disabled,
        .filter-mobile-btn.disabled {
            opacity: 0.5;
            cursor: not-allowed;
            pointer-events: none;
        }

        @media (max-width: 1024px) {
            .catalog-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .catalog-layout {
                grid-template-columns: 1fr;
            }

            .filter-sidebar {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                z-index: 1000;
                border-radius: 0;
                overflow-y: auto;
                padding: 80px 24px 30px;
            }

            .filter-sidebar.show {
                display: block;
            }

            .filter-mobile-btn {
                display: flex;
            }

            .catalog-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 16px;
            }

            .catalog-header {
                flex-direction: column;
                align-items: flex-start;
            }
        }

        @media (max-width: 480px) {
            .catalog-grid {
                grid-template-columns: 1fr;
            }

            .catalog-title h1 {
                font-size: 1.6rem;
            }

            .pagination-item {
                width: 38px;
                height: 38px;
            }

            .pagination-item.next {
                width: auto;
                padding: 0 16px;
            }
        }
    </style>
@endsection

@section('content')
    {!! $breadcrumbs !!}
    <div class="container">
        <!-- Кнопка для мобильного фильтра -->
        <button class="filter-mobile-btn" onclick="document.querySelector('.filter-sidebar').classList.toggle('show')">
            <i class="fas fa-sliders-h"></i> Показать фильтры
        </button>

        <div class="catalog-layout">
            {!! $filter !!}

            <!-- Основная часть каталога -->
            <div class="catalog-content">
                <!-- Верхняя панель -->
                <div class="catalog-header">
                    <div class="catalog-title">
                        <h1>{{ $pagetitle }}</h1>
                    </div>
                </div>

                <!-- Сетка товаров -->
                <div class="catalog-grid" id="eFiltr_results">
                    @foreach ($filterresult['products'] as $item)
                        @include('parts.itemcard', $item)
                    @endforeach
                </div>
                {!! $filterresult['pages'] !!}
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src='assets/js/jquery.min.js'></script>
@endsection

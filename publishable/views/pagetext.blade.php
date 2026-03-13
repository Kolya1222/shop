@extends('layout.app')

@section('styles')
    <style>
        /* Текстовые стили для страницы */
        .text-page {
            max-width: 900px;
            margin: 40px auto;
            padding: 0 20px;
        }

        /* Заголовок страницы */
        .text-page-header {
            display: flex;
            justify-content: space-between;
        }

        .text-page-header h1 {
            font-size: 3rem;
            color: var(--deep-green);
            line-height: 1.2;
            margin-bottom: 20px;
            font-weight: 700;
        }

        .text-page-meta {
            display: flex;
            gap: 30px;
            flex-wrap: wrap;
            color: var(--light-graphite);
            font-size: 1rem;
        }

        .text-page-meta i {
            color: var(--fresh-green);
            margin-right: 8px;
        }

        .text-page-meta span {
            display: flex;
            align-items: center;
        }

        /* Галерея */
        .product-gallery {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .gallery-main {
            background: var(--sage);
            border-radius: 32px;
            padding: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--border-light);
            aspect-ratio: 1/1;
        }

        .gallery-main i {
            font-size: 12rem;
            color: var(--deep-green);
            opacity: 0.9;
        }

        .gallery-thumbnails {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 12px;
        }

        .gallery-thumb {
            background: var(--sage);
            border-radius: 16px;
            padding: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid transparent;
            cursor: pointer;
            transition: 0.2s;
            aspect-ratio: 1/1;
        }

        .gallery-thumb:hover {
            border-color: var(--fresh-green);
            transform: translateY(-2px);
        }

        .gallery-thumb.active {
            border-color: var(--deep-green);
            background: var(--white);
        }

        .gallery-thumb i {
            font-size: 2rem;
            color: var(--deep-green);
        }

        /* Текстовое содержание */
        .text-page-content {
            font-size: 1.1rem;
            line-height: 1.8;
            color: var(--graphite);
        }

        /* Заголовки в тексте */
        .text-page-content h2 {
            font-size: 2rem;
            color: var(--deep-green);
            margin: 40px 0 20px;
            font-weight: 600;
        }

        .text-page-content h3 {
            font-size: 1.5rem;
            color: var(--deep-green);
            margin: 30px 0 15px;
            font-weight: 600;
        }

        .text-page-content h4 {
            font-size: 1.2rem;
            color: var(--deep-green);
            margin: 25px 0 12px;
            font-weight: 600;
        }

        /* Параграфы */
        .text-page-content p {
            margin-bottom: 20px;
        }

        .text-page-content p.lead {
            font-size: 1.3rem;
            color: var(--deep-green);
            font-weight: 500;
            line-height: 1.6;
        }

        /* Списки */
        .text-page-content ul,
        .text-page-content ol {
            margin: 20px 0 20px 30px;
        }

        .text-page-content li {
            margin-bottom: 10px;
        }

        .text-page-content ul li {
            list-style-type: disc;
        }

        .text-page-content ol li {
            list-style-type: decimal;
        }

        /* Цитаты */
        .text-page-content blockquote {
            margin: 30px 0;
            padding: 30px 40px;
            background: var(--sage);
            border-left: 4px solid var(--fresh-green);
            border-radius: 16px;
            font-style: italic;
            color: var(--deep-green);
            font-size: 1.2rem;
            position: relative;
        }

        .text-page-content blockquote::before {
            content: '"';
            position: absolute;
            top: 10px;
            left: 15px;
            font-size: 4rem;
            color: var(--fresh-green);
            opacity: 0.2;
            font-family: serif;
        }

        .text-page-content blockquote p:last-child {
            margin-bottom: 0;
        }

        .text-page-content blockquote cite {
            display: block;
            margin-top: 15px;
            font-size: 0.95rem;
            color: var(--light-graphite);
            font-style: normal;
        }

        /* Таблицы */
        .text-page-content table {
            width: 100%;
            border-collapse: collapse;
            margin: 30px 0;
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid var(--border-light);
        }

        .text-page-content th {
            background: var(--deep-green);
            color: white;
            padding: 15px 20px;
            text-align: left;
            font-weight: 600;
        }

        .text-page-content td {
            padding: 15px 20px;
            border-bottom: 1px solid var(--border-light);
        }

        .text-page-content tr:last-child td {
            border-bottom: none;
        }

        .text-page-content tr:nth-child(even) {
            background: var(--sage);
        }

        /* Изображения в тексте */
        .text-page-content img {
            max-width: 100%;
            height: auto;
            border-radius: 24px;
            margin: 30px 0;
            border: 1px solid var(--border-light);
        }

        .text-page-content .image-left {
            float: left;
            margin: 0 30px 20px 0;
            max-width: 300px;
        }

        .text-page-content .image-right {
            float: right;
            margin: 0 0 20px 30px;
            max-width: 300px;
        }

        /* Информационные блоки */
        .text-page-content .info-box {
            background: var(--sage);
            border-radius: 24px;
            padding: 30px;
            margin: 30px 0;
            border: 1px solid var(--border-light);
        }

        .text-page-content .info-box h4 {
            margin-top: 0;
            color: var(--deep-green);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .text-page-content .info-box h4 i {
            color: var(--fresh-green);
            font-size: 1.5rem;
        }

        .text-page-content .info-box.success {
            background: #d1fae5;
            border-color: var(--fresh-green);
        }

        .text-page-content .info-box.warning {
            background: #fff3cd;
            border-color: #ffc107;
        }

        .text-page-content .info-box.error {
            background: #f8d7da;
            border-color: #dc3545;
        }

        /* Ссылки */
        .text-page-content a {
            color: var(--fresh-green);
            text-decoration: none;
            font-weight: 500;
            border-bottom: 1px solid transparent;
            transition: border-color 0.2s;
        }

        .text-page-content a:hover {
            border-bottom-color: var(--fresh-green);
        }

        /* Выделение */
        .text-page-content mark {
            background: #fef3c7;
            padding: 2px 6px;
            border-radius: 4px;
        }

        .text-page-content strong {
            color: var(--deep-green);
            font-weight: 600;
        }

        /* Кнопки в тексте */
        .text-page-content .text-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: var(--deep-green);
            color: white;
            padding: 14px 32px;
            border-radius: 40px;
            text-decoration: none;
            font-weight: 600;
            transition: 0.2s;
            border: none;
            cursor: pointer;
            margin: 10px 0;
        }

        .text-page-content .text-btn:hover {
            background: var(--fresh-green);
            transform: translateY(-2px);
            box-shadow: var(--hover-shadow);
            border-bottom: none;
        }

        .text-page-content .text-btn-outline {
            background: transparent;
            border: 1px solid var(--deep-green);
            color: var(--deep-green);
        }

        .text-page-content .text-btn-outline:hover {
            background: var(--deep-green);
            color: white;
        }

        /* Теги */
        .text-page-tags {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin: 40px 0;
            padding-top: 30px;
            border-top: 1px solid var(--border-light);
        }

        .text-tag {
            background: var(--sage);
            padding: 8px 20px;
            border-radius: 40px;
            color: var(--deep-green);
            font-size: 0.9rem;
            font-weight: 500;
            text-decoration: none;
            transition: 0.2s;
        }

        .text-tag:hover {
            background: var(--fresh-green);
            color: white;
        }

        /* Шаринг */
        .text-page-share {
            display: flex;
            align-items: center;
            gap: 20px;
            margin: 30px 0;
            padding: 20px 0;
            border-bottom: 1px solid var(--border-light);
        }

        .share-label {
            color: var(--light-graphite);
            font-weight: 500;
        }

        .share-icons {
            display: flex;
            gap: 15px;
        }

        .share-icon {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: var(--sage);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--deep-green);
            text-decoration: none;
            transition: 0.2s;
            font-size: 1.2rem;
        }

        .share-icon:hover {
            background: var(--fresh-green);
            color: white;
            transform: translateY(-3px);
        }

        /* Комментарии */
        .text-page-comments {
            margin: 60px 0;
        }

        .comments-title {
            font-size: 1.8rem;
            color: var(--deep-green);
            margin-bottom: 30px;
        }

        .comment {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
            padding: 30px;
            background: var(--white);
            border-radius: 24px;
            border: 1px solid var(--border-light);
        }

        .comment-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: var(--sage);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--deep-green);
            font-size: 1.8rem;
            flex-shrink: 0;
        }

        .comment-content {
            flex: 1;
        }

        .comment-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .comment-author {
            font-weight: 600;
            color: var(--deep-green);
            font-size: 1.1rem;
        }

        .comment-date {
            color: var(--light-graphite);
            font-size: 0.9rem;
        }

        .comment-text {
            color: var(--graphite);
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .comment-reply {
            color: var(--fresh-green);
            text-decoration: none;
            font-size: 0.95rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .comment-reply:hover {
            text-decoration: underline;
        }

        .comment-form {
            margin-top: 50px;
            padding: 40px;
            background: var(--sage);
            border-radius: 32px;
        }

        .comment-form h3 {
            color: var(--deep-green);
            margin-bottom: 25px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 16px 24px;
            border: 1px solid var(--border-light);
            border-radius: 40px;
            font-family: 'Space Grotesk', sans-serif;
            font-size: 1rem;
            transition: 0.2s;
            background: var(--white);
        }

        .form-group textarea {
            border-radius: 24px;
            resize: vertical;
            min-height: 120px;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--fresh-green);
        }

        .form-submit {
            background: var(--deep-green);
            color: white;
            border: none;
            padding: 16px 40px;
            border-radius: 40px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.2s;
        }

        .form-submit:hover {
            background: var(--fresh-green);
            transform: translateY(-2px);
        }

        /* Похожие статьи */
        .related-articles {
            margin: 60px 0;
        }

        .related-articles h2 {
            font-size: 2rem;
            color: var(--deep-green);
            margin-bottom: 30px;
        }

        .related-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
        }

        .related-card {
            background: var(--white);
            border: 1px solid var(--border-light);
            border-radius: 24px;
            overflow: hidden;
            transition: 0.3s;
        }

        .related-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--card-shadow);
            border-color: var(--fresh-green);
        }

        .related-image {
            height: 180px;
            background: var(--sage);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--deep-green);
            font-size: 4rem;
        }

        .related-content {
            padding: 24px;
        }

        .related-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--deep-green);
            margin-bottom: 10px;
            line-height: 1.4;
        }

        .related-date {
            color: var(--light-graphite);
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* Адаптация */
        @media (max-width: 768px) {
            .text-page-header h1 {
                font-size: 2.2rem;
            }

            .related-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .text-page-content .image-left,
            .text-page-content .image-right {
                float: none;
                max-width: 100%;
                margin: 20px 0;
            }

            .comment {
                flex-direction: column;
                align-items: flex-start;
            }

            .comment-form {
                padding: 25px;
            }
        }

        @media (max-width: 480px) {
            .text-page-header h1 {
                font-size: 1.8rem;
            }

            .related-grid {
                grid-template-columns: 1fr;
            }

            .text-page-meta {
                flex-direction: column;
                gap: 10px;
            }

            .text-page-content blockquote {
                padding: 20px;
            }

            .text-page-content h2 {
                font-size: 1.6rem;
            }

            .text-page-content h3 {
                font-size: 1.3rem;
            }

            .comment {
                padding: 20px;
            }

            .comment-avatar {
                width: 50px;
                height: 50px;
                font-size: 1.5rem;
            }

            .share-icons {
                flex-wrap: wrap;
            }
        }

        /* Заголовки */
        .content-title {
            font-size: 2.2rem;
            color: var(--deep-green, #1a4b3c);
            margin: 30px 0 20px;
            font-weight: 700;
            line-height: 1.3;
        }

        .content-subtitle {
            font-size: 1.6rem;
            color: var(--deep-green, #1a4b3c);
            margin: 25px 0 15px;
            font-weight: 600;
        }

        .content-small-title {
            font-size: 1.2rem;
            color: var(--fresh-green, #2e7d5e);
            margin: 20px 0 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Текст */
        .content-text {
            color: var(--graphite, #334155);
            line-height: 1.7;
            font-size: 1rem;
            margin-bottom: 15px;
        }

        .content-lead {
            font-size: 1.2rem;
            color: var(--deep-green, #1a4b3c);
            font-weight: 500;
            line-height: 1.6;
            margin: 20px 0;
            padding: 15px 20px;
            background: var(--sage, #f0f7f4);
            border-radius: 16px;
            border-left: 4px solid var(--fresh-green, #2e7d5e);
        }

        /* Сетки */
        .content-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            margin: 30px 0;
        }

        .content-grid-2 {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 30px;
            margin: 30px 0;
        }

        .content-grid-3 {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 25px;
            margin: 30px 0;
        }

        /* Карточки */
        .content-card {
            background: var(--white, #ffffff);
            border: 1px solid var(--border-light, #e2e8f0);
            border-radius: 24px;
            padding: 25px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
        }

        .content-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.08);
            border-color: var(--fresh-green, #2e7d5e);
        }

        .content-card-image {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 16px;
            margin-bottom: 20px;
        }

        .content-card-icon {
            width: 60px;
            height: 60px;
            background: var(--sage, #f0f7f4);
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            color: var(--fresh-green, #2e7d5e);
            font-size: 1.8rem;
        }

        .content-card-title {
            font-size: 1.3rem;
            color: var(--deep-green, #1a4b3c);
            margin-bottom: 15px;
            font-weight: 600;
        }

        .content-card-text {
            color: var(--graphite, #334155);
            line-height: 1.6;
            margin-bottom: 15px;
        }

        /* Списки */
        .content-list {
            margin: 20px 0 20px 25px;
        }

        .content-list li {
            color: var(--graphite, #334155);
            margin-bottom: 10px;
            line-height: 1.6;
        }

        .content-list li::marker {
            color: var(--fresh-green, #2e7d5e);
        }

        .content-list-check {
            list-style: none;
            margin-left: 0;
        }

        .content-list-check li {
            position: relative;
            padding-left: 30px;
            margin-bottom: 12px;
        }

        .content-list-check li:before {
            content: "✓";
            position: absolute;
            left: 0;
            color: var(--fresh-green, #2e7d5e);
            font-weight: bold;
            font-size: 1.2rem;
        }

        /* Контактные данные */
        .content-contact-item {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
            padding: 10px 15px;
            background: var(--white, #ffffff);
            border-radius: 16px;
            border: 1px solid var(--border-light, #e2e8f0);
            transition: all 0.2s;
        }

        .content-contact-item:hover {
            border-color: var(--fresh-green, #2e7d5e);
            background: var(--sage, #f0f7f4);
        }

        .content-contact-icon {
            width: 40px;
            height: 40px;
            background: var(--sage, #f0f7f4);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--fresh-green, #2e7d5e);
            font-size: 1.2rem;
        }

        .content-contact-info {
            flex: 1;
            color: var(--graphite, #334155);
            text-decoration: none;
        }

        .content-contact-info a {
            color: var(--fresh-green, #2e7d5e);
            text-decoration: none;
            border-bottom: 1px solid transparent;
            transition: border-color 0.2s;
        }

        .content-contact-info a:hover {
            border-bottom-color: var(--fresh-green, #2e7d5e);
        }

        /* Таблицы */
        .content-table {
            width: 100%;
            border-collapse: collapse;
            margin: 30px 0;
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid var(--border-light, #e2e8f0);
        }

        .content-table th {
            background: var(--deep-green, #1a4b3c);
            color: white;
            padding: 15px 20px;
            text-align: left;
            font-weight: 600;
        }

        .content-table td {
            padding: 12px 20px;
            border-bottom: 1px solid var(--border-light, #e2e8f0);
        }

        .content-table tr:last-child td {
            border-bottom: none;
        }

        .content-table tr:nth-child(even) {
            background: var(--sage, #f0f7f4);
        }

        /* Цитаты */
        .content-quote {
            margin: 30px 0;
            padding: 30px 40px;
            background: var(--sage, #f0f7f4);
            border-radius: 24px;
            font-style: italic;
            color: var(--deep-green, #1a4b3c);
            font-size: 1.1rem;
            position: relative;
            border-left: 4px solid var(--fresh-green, #2e7d5e);
        }

        .content-quote-author {
            display: block;
            margin-top: 15px;
            color: var(--light-graphite, #64748b);
            font-style: normal;
            font-size: 0.95rem;
        }

        /* Информационные блоки */
        .content-info-box {
            background: var(--sage, #f0f7f4);
            border-radius: 20px;
            padding: 25px;
            margin: 25px 0;
            border: 1px solid var(--border-light, #e2e8f0);
        }

        .content-info-box.success {
            background: #d1fae5;
            border-color: var(--fresh-green, #2e7d5e);
        }

        .content-info-box.warning {
            background: #fff3cd;
            border-color: #ffc107;
        }

        .content-info-box.error {
            background: #f8d7da;
            border-color: #dc3545;
        }

        /* Кнопки */
        .content-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: var(--deep-green, #1a4b3c);
            color: white;
            padding: 12px 28px;
            border-radius: 40px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
            margin: 10px 0;
            font-size: 1rem;
        }

        .content-btn:hover {
            background: var(--fresh-green, #2e7d5e);
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(46, 125, 94, 0.2);
            color: white;
        }

        .content-btn-outline {
            background: transparent;
            border: 2px solid var(--deep-green, #1a4b3c);
            color: var(--deep-green, #1a4b3c);
        }

        .content-btn-outline:hover {
            background: var(--deep-green, #1a4b3c);
            color: white;
        }

        .content-btn-small {
            padding: 8px 20px;
            font-size: 0.9rem;
        }

        .content-btn-large {
            padding: 16px 36px;
            font-size: 1.1rem;
        }

        /* Изображения */
        .content-image {
            max-width: 100%;
            height: auto;
            border-radius: 24px;
            margin: 30px 0;
            border: 1px solid var(--border-light, #e2e8f0);
        }

        .content-image-left {
            float: left;
            margin: 0 30px 20px 0;
            max-width: 300px;
            border-radius: 20px;
        }

        .content-image-right {
            float: right;
            margin: 0 0 20px 30px;
            max-width: 300px;
            border-radius: 20px;
        }

        .content-image-caption {
            text-align: center;
            color: var(--light-graphite, #64748b);
            font-size: 0.9rem;
            margin-top: -20px;
            margin-bottom: 30px;
        }

        /* Разделители */
        .content-divider {
            height: 2px;
            background: linear-gradient(to right, transparent, var(--fresh-green, #2e7d5e), transparent);
            margin: 40px 0;
        }

        .content-divider-light {
            height: 1px;
            background: var(--border-light, #e2e8f0);
            margin: 30px 0;
        }

        /* Теги */
        .content-tags {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin: 25px 0;
        }

        .content-tag {
            background: var(--sage, #f0f7f4);
            padding: 6px 16px;
            border-radius: 40px;
            color: var(--deep-green, #1a4b3c);
            font-size: 0.9rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s;
            border: 1px solid transparent;
        }

        .content-tag:hover {
            background: var(--fresh-green, #2e7d5e);
            color: white;
            border-color: var(--fresh-green, #2e7d5e);
        }

        /* Хлебные крошки */
        .content-breadcrumbs {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 25px;
            color: var(--light-graphite, #64748b);
            font-size: 0.95rem;
        }

        .content-breadcrumbs a {
            color: var(--fresh-green, #2e7d5e);
            text-decoration: none;
        }

        .content-breadcrumbs a:hover {
            text-decoration: underline;
        }

        .content-breadcrumbs i {
            font-size: 0.7rem;
            color: var(--border-light, #e2e8f0);
        }

        /* Адаптивность */
        @media (max-width: 768px) {
            .content-title {
                font-size: 1.8rem;
            }

            .content-subtitle {
                font-size: 1.4rem;
            }

            .content-grid-2,
            .content-grid-3 {
                grid-template-columns: 1fr;
            }

            .content-image-left,
            .content-image-right {
                float: none;
                max-width: 100%;
                margin: 20px 0;
            }

            .content-quote {
                padding: 20px;
            }
        }

        @media (max-width: 480px) {
            .content-title {
                font-size: 1.5rem;
            }

            .content-contact-item {
                flex-direction: column;
                text-align: center;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="text-page">
            <!-- Заголовок и мета-информация -->
            <div class="text-page-header">
                <h1>{{ $pagetitle }}</h1>
                <div class="text-page-meta">
                    <span><i class="fas fa-calendar-alt"></i> {{ date('d.m.Y H:i', strtotime($createdon)) }}</span>
                </div>
            </div>

            <!-- Главное изображение -->
            <div class="product-gallery">
                @php
                    $images = json_decode($product_gallery, true)['fieldValue'] ?? [];
                @endphp
                @if (empty($images[0]['image']))
                    <div class="gallery-main">
                        <i class="fas fa-laptop"></i>
                    </div>
                @else
                    <div class="gallery-main">
                        <i class="{{ $images[0]['image'] ?? '' }}"></i>
                    </div>
                @endif
                <div class="gallery-thumbnails">
                    @forelse ($images as $item)
                        <div class="gallery-thumb {{ $loop->first ? 'active' : '' }}" data-icon="{{ $item['image'] }}">
                            <i class="{{ $item['image'] }}"></i>
                        </div>
                    @empty
                        <div class="gallery-thumb active" data-icon="fas fa-laptop">
                            <i class="fas fa-laptop"></i>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Текстовое содержание -->
            <div class="text-page-content">
                {!! $content !!}
            </div>

            <!-- Теги -->
            <div class="text-page-tags">
                @php
                    $tag = json_decode($tag, true)['fieldValue'] ?? [];
                @endphp
                @forelse ($tag as $item)
                    <a href="#" class="text-tag">#{{ $item['value'] }}</a>
                @empty
                    <span>Скоро тут появятся теги</span>
                @endforelse
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mainImage = document.querySelector('.gallery-main i');
            const thumbnails = document.querySelectorAll('.gallery-thumb');

            thumbnails.forEach(thumb => {
                thumb.addEventListener('click', function() {
                    thumbnails.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                    const iconClass = this.dataset.icon;
                    mainImage.className = iconClass;
                });
            });
        });
    </script>
@endsection

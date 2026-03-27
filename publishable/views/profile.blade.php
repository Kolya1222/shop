@extends('layout.app')

@section('styles')
    <style>
        .profile-section {
            padding: 40px 0;
        }

        .profile-header {
            background: linear-gradient(135deg, var(--sage) 0%, var(--mint) 100%);
            border-radius: 32px;
            padding: 48px 40px;
            margin-bottom: 40px;
            position: relative;
            overflow: hidden;
        }

        .profile-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(16, 185, 129, 0.1) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .profile-avatar {
            display: flex;
            align-items: center;
            gap: 24px;
            flex-wrap: wrap;
            position: relative;
            z-index: 1;
        }

        .avatar-circle {
            width: 100px;
            height: 100px;
            background: var(--deep-green);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 3rem;
            font-weight: 600;
            box-shadow: var(--card-shadow);
            border: 4px solid var(--fresh-green);
        }

        .avatar-info h1 {
            font-size: 2rem;
            margin-bottom: 8px;
            color: var(--deep-green);
        }

        .avatar-info p {
            color: var(--light-graphite);
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .avatar-info p i {
            color: var(--fresh-green);
        }

        .member-since {
            font-size: 0.9rem;
            background: rgba(255, 255, 255, 0.8);
            padding: 6px 12px;
            border-radius: 40px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .profile-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 24px;
            margin-bottom: 48px;
        }

        .stat-card {
            background: var(--white);
            border-radius: 24px;
            padding: 24px;
            border: 1px solid var(--border-light);
            transition: all 0.3s ease;
            text-align: center;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--card-shadow);
            border-color: var(--fresh-green);
        }

        .stat-card i {
            font-size: 2.5rem;
            color: var(--fresh-green);
            margin-bottom: 12px;
            display: inline-block;
        }

        .stat-card h3 {
            font-size: 1.8rem;
            color: var(--deep-green);
            margin-bottom: 8px;
        }

        .stat-card p {
            color: var(--light-graphite);
            font-size: 0.9rem;
        }

        .profile-tabs {
            display: flex;
            gap: 12px;
            border-bottom: 2px solid var(--border-light);
            margin-bottom: 32px;
            flex-wrap: wrap;
        }

        .tab-btn {
            padding: 12px 24px;
            background: none;
            border: none;
            font-size: 1rem;
            font-weight: 600;
            color: var(--light-graphite);
            cursor: pointer;
            transition: all 0.2s ease;
            position: relative;
            font-family: 'Space Grotesk', sans-serif;
        }

        .tab-btn:hover {
            color: var(--fresh-green);
        }

        .tab-btn.active {
            color: var(--deep-green);
        }

        .tab-btn.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--fresh-green);
            border-radius: 2px;
        }

        .tab-content {
            display: none;
            animation: fadeIn 0.3s ease;
        }

        .tab-content.active {
            display: block;
        }

        .profile-form {
            max-width: 600px;
            background: var(--white);
            border-radius: 24px;
            padding: 32px;
            border: 1px solid var(--border-light);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--graphite);
        }

        .form-group label i {
            color: var(--fresh-green);
            margin-right: 8px;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid var(--border-light);
            border-radius: 12px;
            font-size: 1rem;
            font-family: 'Space Grotesk', sans-serif;
            transition: all 0.2s ease;
            background: var(--white);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--fresh-green);
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }

        .form-control:disabled {
            background: var(--sage);
            cursor: not-allowed;
        }

        .orders-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .order-card {
            background: var(--white);
            border-radius: 24px;
            border: 1px solid var(--border-light);
            overflow: hidden;
            transition: all 0.2s ease;
        }

        .order-card:hover {
            box-shadow: var(--card-shadow);
            border-color: var(--fresh-green);
        }

        .notification.error {
            border-left-color: #dc2626;
        }

        .notification.success {
            border-left-color: var(--fresh-green);
        }

        @media (max-width: 768px) {
            .profile-header {
                padding: 32px 24px;
            }

            .avatar-circle {
                width: 70px;
                height: 70px;
                font-size: 2rem;
            }

            .avatar-info h1 {
                font-size: 1.5rem;
            }

            .profile-form {
                padding: 20px;
            }

            .form-row {
                grid-template-columns: 1fr;
                gap: 0;
            }
        }

        @media (max-width: 480px) {
            .profile-header {
                padding: 24px 16px;
            }

            .avatar-circle {
                width: 60px;
                height: 60px;
                font-size: 1.6rem;
            }

            .tab-btn {
                padding: 8px 16px;
                font-size: 0.9rem;
            }

            .profile-stats {
                grid-template-columns: 1fr;
            }
        }

        .table {
            width: 100%;
            background: var(--white);
            border-radius: 24px;
            overflow: hidden;
            border-collapse: separate;
            border-spacing: 0;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .table thead {
            background: linear-gradient(135deg, var(--sage) 0%, var(--mint) 100%);
        }

        .table th {
            padding: 16px 20px;
            text-align: left;
            font-weight: 600;
            color: var(--deep-green);
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid var(--fresh-green);
        }

        .table th:first-child {
            border-radius: 20px 0 0 0;
        }

        .table th:last-child {
            border-radius: 0 20px 0 0;
        }

        .table td {
            padding: 20px;
            border-bottom: 1px solid var(--border-light);
            vertical-align: top;
            color: var(--graphite);
            transition: background-color 0.2s ease;
        }

        .table tr:last-child td {
            border-bottom: none;
        }

        .table tr:last-child td:first-child {
            border-radius: 0 0 0 20px;
        }

        .table tr:last-child td:last-child {
            border-radius: 0 0 20px 0;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(209, 250, 229, 0.3);
        }

        .table-striped tbody tr:hover {
            background-color: var(--sage);
            cursor: pointer;
        }

        .table td:first-child {
            font-weight: 700;
            color: var(--deep-green);
            font-size: 1rem;
        }

        .table td:nth-child(3) {
            font-weight: 600;
        }

        .table td:last-child {
            font-weight: 700;
            color: var(--deep-green);
            font-size: 1rem;
            white-space: nowrap;
        }

        .table td:nth-child(5) div {
            font-size: 0.9rem;
            line-height: 1.4;
        }

        .table td:nth-child(5) div span[data-key] {
            display: inline-block;
            margin-top: 6px;
            padding-left: 12px;
            border-left: 2px solid var(--fresh-green);
        }

        .table td:nth-child(5) small {
            color: var(--light-graphite);
            font-size: 0.8rem;
        }

        .table td:nth-child(4) {
            font-size: 0.85rem;
            line-height: 1.5;
        }

        @media (max-width: 768px) {
            .table {
                display: block;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            .table th,
            .table td {
                padding: 12px 16px;
                font-size: 0.85rem;
                white-space: nowrap;
            }

            .table td:first-child,
            .table td:last-child {
                white-space: nowrap;
            }

            .table td:nth-child(5) div {
                white-space: normal;
                min-width: 200px;
            }
        }

        @media (max-width: 480px) {

            .table th,
            .table td {
                padding: 10px 12px;
                font-size: 0.8rem;
            }

            .table td:first-child {
                font-size: 0.85rem;
            }

            .table td:last-child {
                font-size: 0.85rem;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container">
        {!! $breadcrumbs !!}
        <div class="profile-section">
            <div class="profile-header">
                <div class="profile-avatar">
                    <div class="avatar-circle">
                        {{ mb_substr($username, 0, 1) }}
                    </div>
                    <div class="avatar-info">
                        <h1>{{ $username }}</h1>
                        <p>
                            <i class="fas fa-envelope"></i> {{ $email }}
                            <span class="member-since">
                                <i class="fas fa-user"></i>
                                Имя: {{ $username }}
                            </span>
                            <span class="member-since">
                                <i class="fas fa-phone"></i>
                                Номер: {{ $phone }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="profile-tabs">
                <button class="tab-btn active" data-tab="info">
                    <i class="fas fa-user"></i> Личные данные
                </button>
                <button class="tab-btn" data-tab="orders">
                    <i class="fas fa-shopping-bag"></i> Мои заказы
                </button>
            </div>

            <!-- Таб: Личные данные -->
            <div class="tab-content active" id="tab-info">
                <div class="profile-form form-wrapper">
                    <form id="profile-info-form">
                        <input type="hidden" name="formid" value="profile">
                        @csrf
                        <div class="form-row">
                            <div class="form-group" data-field="username">
                                <label><i class="fas fa-user"></i> Имя</label>
                                <input type="text" name="username" class="form-control" value="{{ $username }}">
                            </div>
                            <div class="form-group" data-field="phone">
                                <label><i class="fas fa-phone"></i> Телефон</label>
                                <input type="tel" name="phone" class="form-control" value="{{ $phone }}"
                                    placeholder="+7 (___) ___-__-__">
                            </div>
                        </div>

                        <div class="form-group" data-field="email">
                            <label><i class="fas fa-envelope"></i> Email</label>
                            <input type="email" name="email" class="form-control" value="{{ $email }}">
                        </div>

                        <div class="form-group" data-field="password">
                            <label><i class="fas fa-lock"></i> Новый пароль (оставьте пустым, если не хотите менять)</label>
                            <input type="password" name="password" class="form-control" placeholder="Новый пароль">
                        </div>

                        <div class="form-group" data-field="repeatPassword">
                            <label><i class="fas fa-lock"></i> Подтверждение пароля</label>
                            <input type="password" name="repeatPassword" class="form-control"
                                placeholder="Подтвердите пароль">
                        </div>

                        <button type="submit" class="btn-primary" style="width: 100%;">
                            <i class="fas fa-save"></i> Сохранить изменения
                        </button>
                    </form>
                </div>
            </div>

            <!--Таб: Мои заказы -->
            <div class="tab-content" id="tab-orders">
                <div class="orders-list" id="orders-list">
                    {!! $history !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const tabId = btn.dataset.tab;

                document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
                document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));

                btn.classList.add('active');
                document.getElementById(`tab-${tabId}`).classList.add('active');
            });
        });
    </script>
@endsection

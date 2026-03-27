@guest
    @include('parts.login')
    @include('parts.register')
@endguest
<footer class="footer">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-col">
                <a href="#" class="logo" style="font-size: 1.6rem; margin-bottom: 16px;">
                    <i class="fas fa-cube"></i> Тестовый магазин
                </a>
                <p>Магазин современной IT-техники с быстрой доставкой по всей стране.</p>
                <div class="social-icons">
                    <i class="fab fa-telegram"></i>
                    <i class="fab fa-vk"></i>
                    <i class="fab fa-youtube"></i>
                </div>
            </div>
            <div class="footer-col">
                <h4>Каталог</h4>
                <ul>
                    @foreach ($footermenu as $footermenuitem)
                        <li><a href=@makeUrl($footermenuitem['id'])>{{ $footermenuitem['title'] }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div class="footer-col">
                <h4>Покупателям</h4>
                <ul>
                    @foreach ($footerclient as $item)
                        <li><a href=@makeUrl($item['id'])>{{ $item['title'] }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div class="footer-col">
                <h4>Контакты</h4>
                <ul>
                    <li><a href="tel:@config('client_field_telephone')">@config('client_field_telephone')</a></li>
                    <li><a href="mailto:@config('client_field_mail')">@config('client_field_mail')</a></li>
                    <li>@config('client_field_address')</li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>© 2026 @config('site_name'). Все права защищены. Демонстрационная версия.</p>
        </div>
    </div>
</footer>
<script defer src="assets/plugins/aesearch/aesearch.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        new AESearch();
    })
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        new FormSender();
    })
</script>
@guest
    <script>
        function openLoginModal() {
            closeRegisterModal();
            document.getElementById('loginModal').classList.add('active');
            document.body.classList.add('modal-open');
        }

        function closeLoginModal() {
            document.getElementById('loginModal').classList.remove('active');
            document.body.classList.remove('modal-open');
        }

        function openRegisterModal() {
            closeLoginModal();
            document.getElementById('registerModal').classList.add('active');
            document.body.classList.add('modal-open');
        }

        function closeRegisterModal() {
            document.getElementById('registerModal').classList.remove('active');
            document.body.classList.remove('modal-open');
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('loginModal').addEventListener('click', function(e) {
                if (e.target === this) closeLoginModal();
            });
            document.getElementById('registerModal').addEventListener('click', function(e) {
                if (e.target === this) closeRegisterModal();
            });
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeLoginModal();
                    closeRegisterModal();
                }
            });
            const phoneInput = document.getElementById('reg_phone');
            if (phoneInput) {
                phoneInput.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/\D/g, '');
                    if (value.length === 0) {
                        e.target.value = '';
                        return;
                    }
                    if (value.length === 1) {
                        value = '+7';
                    } else if (value.length <= 4) {
                        value = '+7 (' + value.substring(1, 4);
                    } else if (value.length <= 7) {
                        value = '+7 (' + value.substring(1, 4) + ') ' + value.substring(4, 7);
                    } else if (value.length <= 9) {
                        value = '+7 (' + value.substring(1, 4) + ') ' + value.substring(4, 7) + '-' + value
                            .substring(7, 9);
                    } else {
                        value = '+7 (' + value.substring(1, 4) + ') ' + value.substring(4, 7) + '-' + value
                            .substring(7, 9) + '-' + value.substring(9, 11);
                    }
                    e.target.value = value;
                });
            }
        });
    </script>
@endguest

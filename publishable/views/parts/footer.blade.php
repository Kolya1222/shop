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
                             <li><a href=@makeUrl($footermenuitem['id'])>{{$footermenuitem['title']}}</a></li>
                        @endforeach
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Покупателям</h4>
                    <ul>
                        @foreach ($footerclient as $item)
                            <li><a href=@makeUrl($item['id'])>{{$item['title']}}</a></li>
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
                <p>© 2025 @config('site_name'). Все права защищены. Демонстрационная версия.</p>
            </div>
        </div>
    </footer>
    <script defer src="assets/plugins/aesearch/aesearch.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new AESearch();
        })
    </script>
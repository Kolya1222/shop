<section class="newsletter">
    <h2>Будь в курсе новинок</h2>
    <p>Подпишись и получай новости</p>
    <form data-evocms-user-action="easynewsletter">
        @csrf
        <div class="newsletter-form">
            <input type="email" name="email" placeholder="Введите ваш e-mail" required>
            <button>Подписаться</button>
        </div>
    </form>
</section>

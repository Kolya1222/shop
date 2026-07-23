<section class="newsletter">
    <h2>Будь в курсе новинок</h2>
    <p>Подпишись и получай новости</p>
    <div class="form-wrapper">
        <form>
            @csrf
            <input type="hidden" name="formid" value="newsletter">
            <div class="form-group newsletter-form" data-field="email">
                <input type="email" name="email" placeholder="Введите ваш e-mail" required>
                <button>Подписаться</button>
            </div>
        </form>
    </div>
</section>

</main>
<footer class="footer">
    <div class="footer__container">
        <a href="<?php echo home_url('/'); ?>" class="footer__logo">
            <img src="<?php echo get_template_directory_uri(); ?>/images/footer/logo.svg" alt="Footer logo">
        </a>
        <div class="footer__menu">
            <?php
            wp_nav_menu([
                'theme_location' => 'footer_menu',
                'container' => false,
                'menu_class' => 'footer__menu',
                'fallback_cb' => false,
            ]);
            ?>
        </div>
        <p>&copy;
            <?php echo date('Y'); ?>
            <?php bloginfo('name'); ?>
        </p>
    </div>
</footer>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const burger = document.querySelector('.header__burger');
        const nav = document.querySelector('.header__nav');
        const body = document.body;

        burger.addEventListener('click', (event) => {
            event.stopPropagation();
            burger.classList.toggle('active');
            nav.classList.toggle('active');

            if (burger.classList.contains('active')) {
                body.classList.add('no-scroll');
            } else {
                body.classList.remove('no-scroll');
            }
        });

        document.addEventListener('click', (event) => {
            if (!nav.contains(event.target) && !burger.contains(event.target)) {
                if (nav.classList.contains('active')) {
                    burger.classList.remove('active');
                    nav.classList.remove('active');
                    body.classList.remove('no-scroll');
                }
            }
        });
    });
</script>
<!-- <?php wp_footer(); ?> -->
</body>

</html>
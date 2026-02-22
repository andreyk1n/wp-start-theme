</main>
<footer>
    <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?></p>
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
<?php wp_footer(); ?>
</body>

</html>
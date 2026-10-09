<?php get_header(); ?>

<style>
	.not-found {
		min-height: 60vh;
		padding: 4rem 1.5rem;
		display: grid;
		place-items: center;
		text-align: center;
	}

	.not-found__content {
		max-width: 34rem;
		animation: not-found-appear 600ms ease-out both;
	}

	.not-found__code {
		margin: 0;
		color: #5267d8;
		font-size: clamp(5rem, 20vw, 9rem);
		font-weight: 800;
		line-height: 1;
		letter-spacing: -0.08em;
	}

	.not-found__title {
		margin: 1rem 0 0.75rem;
		font-size: clamp(1.5rem, 5vw, 2.25rem);
	}

	.not-found__text {
		margin: 0 0 1.75rem;
		color: #666;
		line-height: 1.6;
	}

	.not-found__link {
		display: inline-block;
		padding: 0.8rem 1.25rem;
		border-radius: 0.4rem;
		background: #5267d8;
		color: #fff;
		font-weight: 600;
		text-decoration: none;
		transition: background-color 180ms ease, transform 180ms ease;
	}

	.not-found__link:hover {
		background: #4053bd;
		transform: translateY(-2px);
	}

	.not-found__link:focus-visible {
		outline: 3px solid currentColor;
		outline-offset: 4px;
	}

	@keyframes not-found-appear {
		from { opacity: 0; transform: translateY(12px); }
		to { opacity: 1; transform: translateY(0); }
	}

	@media (prefers-reduced-motion: reduce) {
		.not-found__content { animation: none; }
		.not-found__link { transition: none; }
	}
</style>

<main class="not-found">
	<div class="not-found__content">
		<p class="not-found__code" aria-hidden="true">404</p>
		<h1 class="not-found__title">Сторінку не знайдено</h1>
		<p class="not-found__text">На жаль, такої сторінки не існує або її було переміщено.</p>
		<a class="not-found__link" href="<?php echo esc_url( home_url( '/' ) ); ?>">Повернутися на головну</a>
	</div>
</main>

<?php get_footer(); ?>

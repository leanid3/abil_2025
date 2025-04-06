<?php
/* Файл: front-page.php */
get_header();
?>

<main class="container">
    <!-- Блок с турами -->
    <section class="tours-section">
        <div class="container">
            <h2 class="section-title mb-4">Доступные экскурсии</h2>

            <div class="row">
                <?php
                $tours = new WP_Query(array(
                    'post_type' => 'tour',
                    'posts_per_page' => 4,
                    'meta_key' => 'featured',
                    'meta_value' => true
                ));

                while ($tours->have_posts()):
                    $tours->the_post();

                    $image_id = get_post_thumbnail_id();
                    $image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', TRUE) ?: get_the_title();
                    $groups = get_the_terms(get_the_ID(), 'tour_group');
                    $duration = get_field('duration');
                    ?>
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="tour-card card h-100">
                            <!-- Бейджи групп и продолжительности -->
                            <div class="tour-badges">
                                <?php if ($groups): ?>
                                    <span class="badge bg-primary"><?= esc_html($groups[0]->name) ?></span>
                                <?php endif; ?>
                                <?php if ($duration): ?>
                                    <span class="badge bg-secondary"><?= esc_html($duration) ?> ч.</span>
                                <?php endif; ?>
                            </div>

                            <!-- Основное изображение тура -->
                            <?php if ($image_id): ?>
                                <img src="<?php echo wp_get_attachment_image_url($image_id, 'tour_card'); ?>"
                                    class="card-img-top" alt="<?php echo esc_attr($image_alt); ?>">
                            <?php else: ?>
                                <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                                    style="height: 200px;">
                                    <span class="text-muted">Нет изображения</span>
                                </div>
                            <?php endif; ?>

                            <div class="card-body">
                                <h3 class="card-title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h3>

                                <div class="tour-meta">
                                    <?php if ($industry = get_field('industry')): ?>
                                        <div class="industry"><?= esc_html($industry) ?></div>
                                    <?php endif; ?>

                                    <?php if ($organizer = get_field('organizer')): ?>
                                        <div class="organizer">Организатор: <?= esc_html($organizer) ?></div>
                                    <?php endif; ?>
                                </div>

                                <div class="tour-excerpt">
                                    <?php the_excerpt(); ?>
                                </div>
                            </div>

                            <div class="card-footer">
                                <a href="<?php the_permalink(); ?>" class="btn btn-details">Подробнее</a>
                                <button class="book-tour-btn" data-tour-id="<?php the_ID(); ?>"
                                    data-nonce="<?php echo wp_create_nonce('tour_booking_nonce'); ?>">
                                    Записаться
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endwhile;
                wp_reset_postdata(); ?>
            </div>
        </div>
    </section>

    <!-- Блок "Путешественнику" -->
    <section class="row bg-light py-5 mt-4 rounded-3">
        <div class="col-md-8">
            <h3>Рекомендации для посетителей</h3>
            <ul class="list-unstyled">
                <li>✓ Соблюдайте правила безопасности на производстве</li>
                <li>✓ Используйте защитную экипировку</li>
                <li>✓ Следуйте инструкциям гида</li>
            </ul>
        </div>
        <div class="col-md-4 text-center">
            <a href="/tours" class="btn btn-lg btn-danger mt-3">Посмотреть все туры</a>
        </div>
    </section>

    <!-- Сайдбар-блоки -->
    <div class="row mt-5">
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    Календарь экскурсий
                </div>
                <div class="card-body">
                    <?php echo do_shortcode('[calendar id="main"]'); ?>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    Последние новости
                </div>
                <div class="card-body">
                    <?php
                    $news = new WP_Query('posts_per_page=1');
                    if ($news->have_posts()):
                        while ($news->have_posts()):
                            $news->the_post(); ?>
                            <h5><?php the_title(); ?></h5>
                            <?php the_excerpt(); ?>
                        <?php endwhile;
                    endif;
                    wp_reset_postdata(); ?>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    Карта предприятий
                </div>
                <div class="card-body">
                    <div id="map" style="height: 200px">
                        <!-- Здесь будет карта -->
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/map-placeholder.jpg"
                            class="img-fluid rounded" alt="Карта">
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Модальное окно записи -->
<div class="modal fade" id="registrationModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Запись на экскурсию</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <?php echo do_shortcode('[contact-form-7 id="123" title="Форма записи"]'); ?>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
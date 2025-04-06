<?php get_header(); ?>

<div class="single-tour container mt-4">
    <div class="row">
        <div class="col-lg-8">
            <!-- Основное изображение -->
            <div class="tour-main-image mb-4">
                <?php if (has_post_thumbnail()): ?>
                    <?php the_post_thumbnail('full', ['class' => 'img-fluid rounded']); ?>
                <?php else: ?>
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/tour-placeholder-large.jpg"
                        class="img-fluid rounded" alt="<?php the_title(); ?>">
                <?php endif; ?>
            </div>

            <!-- Заголовок и мета-данные -->
            <h1 class="tour-title"><?php the_title(); ?></h1>

            <div class="tour-meta mb-4">
                <?php if ($industry = get_field('industry')): ?>
                    <span class="badge bg-primary me-2"><?= esc_html($industry) ?></span>
                <?php endif; ?>

                <?php if ($duration = get_field('duration')): ?>
                    <span class="me-3"><i class="bi bi-clock"></i> <?= (int) $duration ?> часа</span>
                <?php endif; ?>

                <?php if ($date = get_field('date')): ?>
                    <span class="me-3"><i class="bi bi-calendar"></i> <?= date('d.m.Y', strtotime($date)) ?></span>
                <?php endif; ?>

                <?php if ($time = get_field('time')): ?>
                    <span><i class="bi bi-watch"></i> <?= esc_html($time) ?></span>
                <?php endif; ?>
            </div>

            <!-- Основное содержимое -->
            <div class="tour-content">
                <?php the_content(); ?>

                <!-- Дополнительные данные -->
                <?php if ($address = get_field('address')): ?>
                    <div class="tour-address card mt-4">
                        <div class="card-header">
                            <h3><i class="bi bi-geo-alt"></i> Адрес проведения</h3>
                        </div>
                        <div class="card-body">
                            <p><?= nl2br(esc_html($address)) ?></p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Сайдбар -->
        <div class="col-lg-4">
            <div class="tour-sidebar">
                <!-- Организатор -->
                <?php if ($organizer = get_field('organizer')): ?>
                    <div class="card mb-4">
                        <div class="card-header">
                            <h3>Организатор</h3>
                        </div>
                        <div class="card-body">
                            <h4><?= esc_html($organizer) ?></h4>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Форма записи -->
                <button class="book-tour-btn" data-tour-id="<?php the_ID(); ?>"
                    data-nonce="<?php echo wp_create_nonce('tour_booking_nonce'); ?>">
                    Записаться
                </button>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
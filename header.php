<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>

<body>
    <header class="container">
        <div class="py-3 text-center">
            <?php if (has_custom_logo()): ?>
                <?php the_custom_logo(); ?>
            <?php else: ?>
                <h1>Маршруты будущего</h1>
            <?php endif; ?>
        </div>

        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <?php wp_nav_menu(array(
                'theme_location' => 'main-menu',
                'container_class' => 'collapse navbar-collapse',
                'menu_class' => 'navbar-nav me-auto mb-2 mb-lg-0'
            )); ?>
        </nav>

        <div class="mb-4">
            <?php echo do_shortcode('[metaslider id="123"]'); ?>
        </div>
    </header>
    <main class="container"></main>
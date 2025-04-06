<?php 


// Регистрация меню
register_nav_menus(array(
    'main-menu' => 'Главное меню'
));


// Шорткод для спойлеров
function spoiler_shortcode($atts, $content = null) {
    return '<div class="spoiler mb-3">
        <button class="btn btn-secondary spoiler-toggle">ПОЛНОСТЬЮ</button>
        <div class="spoiler-content mt-2">'.do_shortcode($content).'</div>
    </div>';
}
add_shortcode('spoiler', 'spoiler_shortcode');

// Кастомизация логотипа
add_theme_support('custom-logo');


// Добавляем поддержку плагинов
function theme_setup() {
    // Поддержка виджетов
    register_sidebar(array(
        'name' => 'Сайдбар',
        'id' => 'sidebar',
        'before_widget' => '<div class="widget mb-4">',
        'after_widget' => '</div>'
    ));

    // Таксономия для групп
    register_taxonomy('tour_group', 'tour', array(
        'label' => 'Группы',
        'hierarchical' => true
    ));
}
add_action('after_setup_theme', 'theme_setup');

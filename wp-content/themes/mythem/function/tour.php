<?php function create_tour_post_type()
{
    $labels = array(
        'name' => 'Туры',
        'singular_name' => 'Тур',
        'add_new' => 'Добавить тур',
        'add_new_item' => 'Добавить новый тур',
        'edit_item' => 'Редактировать тур',
        'featured_image' => 'Изображение тура',
        'set_featured_image' => 'Установить изображение',
        'remove_featured_image' => 'Удалить изображение'
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-admin-site-alt3',
        'supports' => array(
            'title',
            'editor',
            'thumbnail', // Включает поддержку featured image
            'custom-fields',
            'excerpt'
        ),
        'show_in_rest' => true,
        'rewrite' => array('slug' => 'tour')
    );

    register_post_type('tour', $args);
}
add_action('init', 'create_tour_post_type');

function setup_tour_images()
{
    add_theme_support('post-thumbnails');

    // Основной размер для карточек
    add_image_size('tour-card', 400, 300, true);

    // Размер для галереи
    add_image_size('tour-gallery', 800, 600, true);

    // Размер для слайдера
    add_image_size('tour-slider', 1200, 500, true);
}

// Добавляем размеры изображений
add_action('after_setup_theme', 'setup_tour_images');

function create_tour_groups_taxonomy()
{
    register_taxonomy('tour_group', 'tour', array(
        'label' => 'Группы',
        'hierarchical' => true,
        'public' => true,
        'show_in_rest' => true
    ));
}
add_action('init', 'create_tour_groups_taxonomy');



// Фильтр туров
function tours_filter_shortcode()
{
    ob_start(); ?>
    <form class="mb-4">
        <select class="form-select" onchange="location=this.value">
            <option value="#">Фильтр по группам</option>
            <?php
            $terms = get_terms('tour_group');
            foreach ($terms as $term): ?>
                <option value="<?= get_term_link($term) ?>"><?= $term->name ?></option>
            <?php endforeach; ?>
        </select>
    </form>
    <?php return ob_get_clean();
}
add_shortcode('tours_filter', 'tours_filter_shortcode');

function tours_scripts()
{
    wp_enqueue_script('tours-ajax', get_template_directory_uri() . '/assets/js/tours.js', ['jquery'], null, true);
    wp_localize_script('tours-ajax', 'tours_vars', [
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('tours_nonce')
    ]);
}
add_action('wp_enqueue_scripts', 'tours_scripts');


function filter_tours_callback()
{
    check_ajax_referer('tours_nonce', 'nonce');

    $args = [
        'post_type' => 'tour',
        'posts_per_page' => -1,
        'tax_query' => [],
        'meta_query' => []
    ];

    if (!empty($_POST['groups'])) {
        $args['tax_query'][] = [
            'taxonomy' => 'tour_group',
            'field' => 'term_id',
            'terms' => $_POST['groups']
        ];
    }

    if (!empty($_POST['date'])) {
        $args['meta_query'][] = [
            'key' => 'date',
            'value' => $_POST['date'],
            'compare' => '='
        ];
    }

    $query = new WP_Query($args);

    if ($query->have_posts()):
        while ($query->have_posts()):
            $query->the_post();
            // Вывод карточки тура как в archive-tour.php
            get_template_part('template-parts/tour', 'card');
        endwhile;
    else:
        echo '<div class="col-12"><p>Туры не найдены</p></div>';
    endif;

    wp_die();
}
// Фильтрация туров
add_action('wp_ajax_filter_tours', 'filter_tours_callback');
add_action('wp_ajax_nopriv_filter_tours', 'filter_tours_callback');

if (function_exists('acf_add_local_field_group')):
    acf_add_local_field_group(array(
        'key' => 'group_tour_fields',
        'title' => 'Данные тура',
        'position' => 'acf_after_title',
        'style' => 'seamless',
        'fields' => array(
            // Основные поля
            array(
                'key' => 'field_industry',
                'label' => 'Отрасль промышленности',
                'name' => 'industry',
                'type' => 'text',
                'required' => 1
            ),
            array(
                'key' => 'field_organizer',
                'label' => 'Организатор тура',
                'name' => 'organizer',
                'type' => 'text',
                'required' => 1
            ),
            array(
                'key' => 'field_duration',
                'label' => 'Продолжительность (часы)',
                'name' => 'duration',
                'type' => 'number',
                'min' => 1
            ),

            // Даты и время
            array(
                'key' => 'field_date',
                'label' => 'Дата проведения',
                'name' => 'date',
                'type' => 'date_picker',
                'display_format' => 'd.m.Y',
                'return_format' => 'Y-m-d'
            ),
            array(
                'key' => 'field_time',
                'label' => 'Время начала',
                'name' => 'time',
                'type' => 'time_picker',
                'display_format' => 'H:i'
            ),

            // Местоположение
            array(
                'key' => 'field_address',
                'label' => 'Адрес предприятия',
                'name' => 'address',
                'type' => 'textarea',
                'rows' => 2
            ),
            array(
                'key' => 'field_map',
                'label' => 'Код карты',
                'name' => 'map',
                'type' => 'textarea',
                'instructions' => 'Вставьте iframe карты'
            ),

            // Галерея
            array(
                'key' => 'field_main_image',
                'label' => 'Основное изображение',
                'name' => 'main_image',
                'type' => 'image',
                'return_format' => 'id',
                'preview_size' => 'medium',
                'library' => 'all',
                'wrapper' => array(
                    'width' => '50%'
                )
            ),
            // Настройки
            array(
                'key' => 'field_featured',
                'label' => 'Показывать на главной',
                'name' => 'featured',
                'type' => 'true_false',
                'ui' => 1
            )
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'tour'
                )
            )
        )
    ));
endif;
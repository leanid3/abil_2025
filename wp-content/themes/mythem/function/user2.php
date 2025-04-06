<?php 

/**
 * Регистрация страниц при активации темы
 */
function create_auth_pages() {
    $pages = array(
        'register' => 'Регистрация',
        'login'    => 'Авторизация',
        'account'  => 'Личный кабинет'
    );

    foreach ($pages as $slug => $title) {
        if (!get_page_by_path($slug)) {
            wp_insert_post(array(
                'post_title'   => $title,
                'post_name'    => $slug,
                'post_status'  => 'publish',
                'post_type'    => 'page',
                'post_content' => '[custom_' . $slug . '_form]'
            ));
        }
    }
}
add_action('after_switch_theme', 'create_auth_pages');

/**
 * Шорткод формы регистрации
 */
add_shortcode('custom_register_form', 'register_form_shortcode');
function register_form_shortcode() {
    if (is_user_logged_in()) {
        return '<p>Вы уже зарегистрированы</p>';
    }

    ob_start(); ?>
    <div class="auth-container">
        <form id="register-form" class="auth-form" enctype="multipart/form-data">
            <h2>РЕГИСТРАЦИЯ</h2>
            
            <div class="form-group">
                <label>Введите имя</label>
                <input type="text" name="first_name" required>
            </div>

            <div class="form-group">
                <label>Введите фамилию</label>
                <input type="text" name="last_name" required>
            </div>

            <div class="form-group">
                <label>Введите почту</label>
                <input type="email" name="email" required>
            </div>

            <div class="form-group">
                <label>Выберите аватар</label>
                <input type="file" name="avatar" accept="image/*">
            </div>

            <div class="form-group">
                <label>Введите пароль</label>
                <input type="password" name="password" required>
            </div>

            <div class="form-group">
                <label>Повторите пароль</label>
                <input type="password" name="confirm_password" required>
            </div>

            <div class="form-checkbox">
                <input type="checkbox" id="terms" required>
                <label for="terms">Принимаю соглашение</label>
            </div>

            <?php wp_nonce_field('register_nonce', 'register_nonce'); ?>
            <button type="submit" class="auth-button">Зарегистрироваться</button>
        </form>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * Шорткод формы авторизации
 */
add_shortcode('custom_login_form', 'login_form_shortcode');
function login_form_shortcode() {
    if (is_user_logged_in()) {
        return '<p>Вы уже авторизованы</p>';
    }

    ob_start(); ?>
    <div class="auth-container">
        <form id="login-form" class="auth-form">
            <h2>АВТОРИЗАЦИЯ</h2>
            
            <div class="form-group">
                <label>Введите почту</label>
                <input type="email" name="email" required>
            </div>

            <div class="form-group">
                <label>Введите пароль</label>
                <input type="password" name="password" required>
            </div>

            <?php wp_nonce_field('login_nonce', 'login_nonce'); ?>
            <button type="submit" class="auth-button">Войти</button>
            
            <div class="auth-links">
                <a href="<?php echo wp_lostpassword_url(); ?>">Забыли пароль?</a>
                <a href="<?php echo home_url('/register'); ?>">Регистрация</a>
            </div>
        </form>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * Шорткод личного кабинета
 */
add_shortcode('custom_account', 'account_shortcode');
function account_shortcode() {
    if (!is_user_logged_in()) {
        return '<p>Пожалуйста <a href="' . home_url('/login') . '">войдите</a> для просмотра профиля</p>';
    }

    $user = wp_get_current_user();
    $avatar_id = get_user_meta($user->ID, 'avatar', true);
    $bookings = get_user_meta($user->ID, 'tour_bookings', true) ?: array();

    ob_start(); ?>
    <div class="account-container">
        <div class="profile-header">
            <?php if ($avatar_id) : ?>
                <div class="profile-avatar">
                    <?php echo wp_get_attachment_image($avatar_id, 'medium'); ?>
                </div>
            <?php endif; ?>
            
            <h2><?php echo esc_html($user->first_name . ' ' . $user->last_name); ?></h2>
            <p><?php echo esc_html($user->user_email); ?></p>
        </div>

        <div class="account-section">
            <h3>Мои записи на туры</h3>
            
            <?php if (!empty($bookings)) : ?>
                <div class="bookings-grid">
                    <?php foreach ($bookings as $booking) : 
                        $tour = get_post($booking['tour_id']);
                        if ($tour) :
                    ?>
                        <div class="booking-card">
                            <h4><?php echo esc_html($tour->post_title); ?></h4>
                            <p class="booking-date">Дата: <?php echo date('d.m.Y', strtotime($booking['date'])); ?></p>
                            <p class="booking-status">Статус: <?php echo esc_html($booking['status'] ?? 'Подтверждено'); ?></p>
                        </div>
                    <?php 
                        endif;
                    endforeach; ?>
                </div>
            <?php else : ?>
                <p class="no-bookings">У вас пока нет записей на туры</p>
            <?php endif; ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

// Обработка регистрации
add_action('wp_ajax_nopriv_custom_register', 'handle_registration');
function handle_registration() {
    check_ajax_referer('register_nonce', 'register_nonce');

    // Валидация данных
    $userdata = array(
        'first_name' => sanitize_text_field($_POST['first_name']),
        'last_name'  => sanitize_text_field($_POST['last_name']),
        'user_email' => sanitize_email($_POST['email']),
        'user_pass'  => $_POST['password'],
        'role'       => 'subscriber'
    );

    // Создание пользователя
    $user_id = wp_insert_user($userdata);

    if (!is_wp_error($user_id)) {
        // Загрузка аватара
        if (!empty($_FILES['avatar'])) {
            require_once(ABSPATH . 'wp-admin/includes/image.php');
            require_once(ABSPATH . 'wp-admin/includes/file.php');
            require_once(ABSPATH . 'wp-admin/includes/media.php');

            $attachment_id = media_handle_upload('avatar', 0);
            if (!is_wp_error($attachment_id)) {
                update_user_meta($user_id, 'avatar', $attachment_id);
            }
        }

        // Автовход
        wp_set_auth_cookie($user_id);
        wp_send_json_success(array(
            'redirect' => home_url('/account')
        ));
    } else {
        wp_send_json_error(array(
            'message' => $user_id->get_error_message()
        ));
    }
}

// Обработка авторизации
add_action('wp_ajax_nopriv_custom_login', 'handle_login');
function handle_login() {
    check_ajax_referer('login_nonce', 'login_nonce');

    $creds = array(
        'user_login'    => sanitize_email($_POST['email']),
        'user_password' => $_POST['password'],
        'remember'      => true
    );

    $user = wp_signon($creds, false);

    if (!is_wp_error($user)) {
        wp_send_json_success(array(
            'redirect' => home_url('/account')
        ));
    } else {
        wp_send_json_error(array(
            'message' => 'Неверный email или пароль'
        ));
    }
}
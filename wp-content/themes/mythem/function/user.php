<?php 

// Шорткод для формы регистрации
add_shortcode('custom_register_form', 'custom_register_form_shortcode');
function custom_register_form_shortcode() {
    ob_start(); ?>
    
    <form id="custom-register-form" class="auth-form" enctype="multipart/form-data">
        <div class="form-group">
            <label>Имя</label>
            <input type="text" name="first_name" required>
        </div>

        <div class="form-group">
            <label>Фамилия</label>
            <input type="text" name="last_name" required>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" required>
        </div>

        <div class="form-group">
            <label>Аватар</label>
            <input type="file" name="avatar" accept="image/*">
        </div>

        <div class="form-group">
            <label>Пароль</label>
            <input type="password" name="password" required>
        </div>

        <div class="form-group">
            <label>Повторите пароль</label>
            <input type="password" name="confirm_password" required>
        </div>

        <div class="form-check">
            <input type="checkbox" id="agree" required>
            <label for="agree">Принимаю соглашение</label>
        </div>

        <?php wp_nonce_field('custom_register_nonce', 'register_nonce'); ?>
        <button type="submit">Зарегистрироваться</button>
    </form>

    <?php return ob_get_clean();
}

// Обработка регистрации
add_action('wp_ajax_custom_register', 'handle_custom_registration');
add_action('wp_ajax_nopriv_custom_register', 'handle_custom_registration');

function handle_custom_registration() {
    // Проверка безопасности
    check_ajax_referer('custom_register_nonce', 'security');

    // Валидация данных
    $userdata = array(
        'first_name' => sanitize_text_field($_POST['first_name']),
        'last_name'  => sanitize_text_field($_POST['last_name']),
        'user_email' => sanitize_email($_POST['email']),
        'user_pass'  => $_POST['password']
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
            update_user_meta($user_id, 'avatar', $attachment_id);
        }

        // Автовход
        wp_set_auth_cookie($user_id);
        wp_send_json_success(array('redirect' => home_url('/личный-кабинет')));
    } else {
        wp_send_json_error(array('message' => $user_id->get_error_message()));
    }
}

add_shortcode('custom_login_form', 'custom_login_form_shortcode');
function custom_login_form_shortcode() {
    ob_start(); ?>
    
    <form id="custom-login-form" class="auth-form">
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" required>
        </div>

        <div class="form-group">
            <label>Пароль</label>
            <input type="password" name="password" required>
        </div>

        <?php wp_nonce_field('custom_login_nonce', 'login_nonce'); ?>
        <button type="submit">Войти</button>
        <a href="<?php echo wp_lostpassword_url(); ?>">Забыли пароль?</a>
    </form>

    <?php return ob_get_clean();
}

// Обработка записи
add_action('wp_ajax_book_tour', 'handle_tour_booking');
add_action('wp_ajax_nopriv_book_tour', 'handle_tour_booking');

function handle_tour_booking() {
    check_ajax_referer('tour_booking_nonce', 'security');

    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => 'Требуется авторизация'));
    }

    $user_id = get_current_user_id();
    $booking_data = array(
        'tour_id' => intval($_POST['tour_id']),
        'date' => date('Y-m-d H:i:s')
    );

    $bookings = get_user_meta($user_id, 'tour_bookings', true) ?: array();
    $bookings[] = $booking_data;
    update_user_meta($user_id, 'tour_bookings', $bookings);

    wp_send_json_success(array('message' => 'Вы успешно записаны!'));
}
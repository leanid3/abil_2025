<div class="personal-account">
    <?php if (is_user_logged_in()) : 
        $user = wp_get_current_user();
        $avatar_id = get_user_meta($user->ID, 'avatar', true);
    ?>
        <div class="profile-header">
            <?php if ($avatar_id) : ?>
                <div class="avatar">
                    <?php echo wp_get_attachment_image($avatar_id, 'thumbnail'); ?>
                </div>
            <?php endif; ?>
            
            <h2><?php echo $user->first_name . ' ' . $user->last_name; ?></h2>
            <p><?php echo $user->user_email; ?></p>
        </div>

        <div class="bookings-list">
            <h3>Мои записи</h3>
            <?php 
            $bookings = get_user_meta($user->ID, 'tour_bookings', true);
            if (!empty($bookings)) : 
                foreach ($bookings as $booking) :
                    $tour = get_post($booking['tour_id']);
            ?>
                <div class="booking-item">
                    <h4><?php echo $tour->post_title; ?></h4>
                    <p>Дата: <?php echo $booking['date']; ?></p>
                </div>
            <?php endforeach; else : ?>
                <p>У вас нет активных записей</p>
            <?php endif; ?>
        </div>

    <?php else : ?>
        <p>Пожалуйста, <a href="/войти">войдите</a> чтобы просмотреть свой профиль</p>
    <?php endif; ?>
</div>
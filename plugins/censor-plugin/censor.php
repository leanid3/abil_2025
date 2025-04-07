<?php
/*
Plugin Name: Цензор
*/

add_filter('wpcf7_validate_textarea', 'censor_validation', 10, 2);

function censor_validation($result, $tag) {
    $forbidden = ['козёл', 'козел', 'дурак'];
    $message = wpcf7_get_posted_data()['your-message'];
    
    foreach($forbidden as $word) {
        if(stripos($message, $word) !== false) {
            $result->invalidate($tag, 'Недопустимое слово в сообщении');
            $message = str_ireplace($word, '<span style="color:red">***</span>', $message);
            $_POST['your-message'] = $message;
        }
    }
    return $result;
}
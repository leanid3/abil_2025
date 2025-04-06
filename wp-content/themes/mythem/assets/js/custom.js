jQuery(document).ready(function($) {
    // Фильтрация туров
    $('#tourFilters').on('submit', function(e) {
        e.preventDefault();
        
        let data = {
            action: 'filter_tours',
            nonce: tours_vars.nonce,
            groups: $('input[name="groups[]"]:checked').map(function() {
                return this.value;
            }).get(),
            date: $('#tourDate').val()
        };
        
        $.post(tours_vars.ajaxurl, data, function(response) {
            $('#tourResults').html(response);
        });
    });
    
    // Сортировка
    $('#tourSort').on('change', function() {
        let order = $(this).val().split('-');
        
        let data = {
            action: 'sort_tours',
            nonce: tours_vars.nonce,
            orderby: order[0],
            order: order[1]
        };
        
        $.post(tours_vars.ajaxurl, data, function(response) {
            $('#tourResults').html(response);
        });
    });
});


jQuery(document).on('click', '.book-tour-btn', function() {
    var data = {
        action: 'book_tour',
        tour_id: jQuery(this).data('tour-id'),
        security: jQuery(this).data('nonce')
    };

    jQuery.post(ajaxurl, data, function(response) {
        if (response.success) {
            alert(response.data.message);
        } else {
            alert(response.data.message);
        }
    });
});

// Регистрация
jQuery('#register-form').on('submit', function(e) {
    e.preventDefault();
    
    var formData = new FormData(this);
    formData.append('action', 'custom_register');
    formData.append('register_nonce', jQuery('#register-form input[name="register_nonce"]').val());

    jQuery.ajax({
        url: ajaxurl,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.success) {
                window.location.href = response.data.redirect;
            } else {
                alert(response.data.message);
            }
        }
    });
});

// Авторизация
jQuery('#login-form').on('submit', function(e) {
    e.preventDefault();
    
    var formData = {
        action: 'custom_login',
        email: jQuery('#login-form input[name="email"]').val(),
        password: jQuery('#login-form input[name="password"]').val(),
        login_nonce: jQuery('#login-form input[name="login_nonce"]').val()
    };

    jQuery.post(ajaxurl, formData, function(response) {
        if (response.success) {
            window.location.href = response.data.redirect;
        } else {
            alert(response.data.message);
        }
    });
});
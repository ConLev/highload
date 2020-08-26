$(document).ready(() => {

    function request(path, data, cb) {
        $.post({
            url: path,
            data: data,
            success: function (data) {
                //Вариант с json
                if (data.error) {
                    message(data['error_text']);
                } else {
                    cb(data.data);
                }
            }
        })
    }

    function message(message) {
        $message_field.text(message);

        setTimeout(() => {
            $message_field.text('');
        }, 3000);
    }

    function login() {
        //Получаем input'ы логина и пароля
        const $login_input = $('[name="login"]');
        const $password_input = $('[name="password"]');

        //Получаем значение login и password
        const login = $login_input.val();
        const password = $password_input.val();

        request('/api/authentication/login/', {
            login: login,
            password: password
        }, function () {
            location.replace('/account/');
        });
    }

    $('.box-product').on('click', '.item-add_link_top', e => {
        e.preventDefault();
        request('/api/cart/add/', {
            id: $(e.currentTarget).data('id'),
            price: $(e.currentTarget).data('price'),
            discount: $(e.currentTarget).data('discount'),
        }, function () {
        });
    });

    $('.cart-product-quantity').on('click', '.cart-quantity-value', e => {
        request('/api/cart/update/', {
            id: $(e.currentTarget).data('id'),
            quantity: (e.currentTarget).value,
            price: $(e.currentTarget).data('price'),
            discount: $(e.currentTarget).data('discount'),
        }, function () {
            location.reload();
        });
    });

    $('.product-box-details').on('click', '.cart-product-remBtn', e => {
        e.preventDefault();
        request('/api/cart/remove/', {
            product_id: $(e.currentTarget).data('id'),
        }, function () {
            location.reload();
        });
    });

    const $cartButton = $('.shopping-cart-button');

    $cartButton.on('click', '.shopping-cart-button_clear', () => {

        request('/api/cart/clear/', {}, function () {
            location.reload();
        });
    });

    $('.shopping-cart-button_createOrder').on('click', e => {
        request('/api/orders/createOrder/', {}, function () {
            location.reload();
        });
    });

    const $orderStatus = $('.order_thead');

    $orderStatus.on('click', '.order_status_value', e => {
        request('/api/orders/updateStatus/', {
            id: $(e.currentTarget).data('id'),
            user_id: $(e.currentTarget).data('user_id'),
            address: $(e.currentTarget).data('address'),
            date_create: $(e.currentTarget).data('date_create'),
            status: (e.currentTarget).value,
        }, function () {
            location.reload();
        });
    });

    $orderStatus.on('click', '.user_order_cancel', e => {
        request('/api/orders/updateStatus/', {
            id: $(e.currentTarget).data('id'),
            user_id: $(e.currentTarget).data('user_id'),
            address: $(e.currentTarget).data('address'),
            date_create: $(e.currentTarget).data('date_create'),
            status: $(e.currentTarget).data('status'),
        }, function () {
            location.reload();
        });
    });

    $('.remove_product_of_order').on('click', e => {
        request('/api/orders/deleteProductOfOrder/', {
            order_id: $(e.currentTarget).data('order_id'),
            product_id: $(e.currentTarget).data('product_id'),
        }, function () {
            location.reload();
        });
    });

    $('.remove_order').on('click', e => {
        request('/api/orders/removeOrder/', {
            order_id: $(e.currentTarget).data('order_id'),
        }, function () {
            location.reload();
        });
    });

    //Инициализируем поле для сообщений
    const $message_field = $('.message');

    //Login
    const $loginBtn = $('.user_account_submit');
    $loginBtn.on('click', login);
});
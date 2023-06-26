<?php 

$widgets = [
    'widget-text.php',
    'widget-contacts.php',
    'widget-social.php',
    'widget-iframe.php',
    'widget-info.php',
];

foreach ($widgets as $item) {
    require_once __DIR__ . '/inc/' . $item;
}

add_action('after_setup_theme', 'si_setup');
add_action('wp_enqueue_scripts', 'si_scripts');
add_action('widgets_init', 'si_register');
add_action('init', 'si_register_types', );
add_action('add_meta_boxes', 'si_meta_boxes');
//add_action('save_post', 'si_save_like_meta'); // ручное добавление лайков из админки, потом заменили на механизм лайков от пользователей
add_action('admin_post_nopriv_si-modal-form', 'si_modal_form_handler');
add_action('admin_post_si-modal-form', 'si_modal_form_handler');
add_action('wp_ajax_nopriv_post-likes', 'si_likes');
add_action('wp_ajax_post-likes', 'si_likes');
add_action('manage_posts_custom_column', 'si_like_column', 5, 2); // выведет данные лайков в кастомную колонку на странице записей в админке
add_action('manage_posts_custom_column', 'si_order_column', 5, 2); // выведет статус заявки в кастомную колонку на странице заявок в админке

add_shortcode('si-paste-link', 'si_paste_link'); // добавляет шорткод [si-paste-link], когда его находит в тексте, вызывает функцию si_paste_link

// add_filter('show_admin_bar', '__return_false'); // убирает верхнюю панель администратора на странице
add_filter('si_widget_text_filter', 'do_shortcode'); // фильтр c именем для si_widget_text_filter, вызывает встроенную функцию do_shortcode, которая добавляет поддержку шорткодов в тексте
add_filter('manage_posts_columns', 'si_add_col_likes'); // создаст кастомную колонку
add_filter('manage_posts_columns', 'si_add_col_orders'); // создаст кастомную колонку

function si_setup() {
    register_nav_menu('menu-header', 'Меню в шапке'); // первый аргумент это theme_location
    register_nav_menu('menu-footer', 'Меню в подвале');

    add_theme_support('custom-logo');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
}

function si_scripts() {
    wp_enqueue_script('si-js', _si_assets_path('js/js.js'), [], '1.0', true);
    wp_enqueue_style('si-style', _si_assets_path('css/styles.css'), [], '1.0', 'all');
}

function si_register() {
    register_sidebar([
        'name'          => 'Контакты в шапке сайта',
		'id'            => 'si-header',
        'before_widget' => null,
        'after_widget' => null,
    ]);
    register_sidebar([
        'name'          => 'Контакты в подвале сайта',
		'id'            => 'si-footer',
        'before_widget' => null,
        'after_widget' => null,
    ]);
    register_sidebar([
        'name'          => 'Сайдбар в футере - Колонка 1',
		'id'            => 'si-footer-column-1',
        'before_widget' => null,
        'after_widget' => null,
    ]);
    register_sidebar([
        'name'          => 'Сайдбар в футере - Колонка 2',
		'id'            => 'si-footer-column-2',
        'before_widget' => null,
        'after_widget' => null,
    ]);
    register_sidebar([
        'name'          => 'Сайдбар в футере - Колонка 3',
		'id'            => 'si-footer-column-3',
        'before_widget' => null,
        'after_widget' => null,
    ]);
    register_sidebar([
        'name'          => 'Карта',
		'id'            => 'si-map',
        'before_widget' => null,
        'after_widget' => null,
    ]);
    register_sidebar([
        'name'          => 'Сайдбар под картой',
		'id'            => 'si-after-map',
        'before_widget' => null,
        'after_widget' => null,
    ]);
    register_sidebar([
        'name'          => 'Главная страница',
		'id'            => 'si-main-page',
        'before_widget' => null,
        'after_widget' => null,
    ]);
    register_widget('si_widget_text');
    register_widget('si_widget_contacts');
    register_widget('si_widget_social');
    register_widget('si_widget_iframe');
    register_widget('si_widget_info');
}

function si_register_types() {
    register_post_type('services', [
		'labels' => [
			'name'               => 'Услуги', // основное название для типа записи
			'singular_name'      => 'Услуга', // название для одной записи этого типа
			'add_new'            => 'Добавить новую услугу', // для добавления новой записи
			'add_new_item'       => 'Добавление услуги', // заголовка у вновь создаваемой записи в админ-панели.
			'edit_item'          => 'Редактирование услуги', // для редактирования типа записи
			'new_item'           => 'Новая услуга', // текст новой записи
			'view_item'          => 'Смотреть услуги', // для просмотра записи этого типа.
			'search_items'       => 'Искать услуги', // для поиска по этим типам записи
			'not_found'          => 'Не найдено', // если в результате поиска ничего не было найдено
			'not_found_in_trash' => 'Не найдено в корзине', // если не было найдено в корзине
			'parent_item_colon'  => '', // для родителей (у древовидных типов)
			'menu_name'          => 'Услуги', // название меню
		],
		'public'                 => true,
        'menu_icon'           => 'dashicons-coffee',
		'supports'            => ['title'], // 'title','editor','author','thumbnail','excerpt','trackbacks','custom-fields','comments','revisions','page-attributes','post-formats'
		'has_archive'         => true,
	]);
    register_post_type('trainers', [
		'labels' => [
			'name'               => 'Тренеры', // основное название для типа записи
			'singular_name'      => 'Тренер', // название для одной записи этого типа
			'add_new'            => 'Добавить нового тренера', // для добавления новой записи
			'add_new_item'       => 'Добавление тренера', // заголовка у вновь создаваемой записи в админ-панели.
			'edit_item'          => 'Редактирование тренера', // для редактирования типа записи
			'new_item'           => 'Новый тренер', // текст новой записи
			'view_item'          => 'Смотреть тренера', // для просмотра записи этого типа.
			'search_items'       => 'Искать тренеров', // для поиска по этим типам записи
			'not_found'          => 'Не найдено', // если в результате поиска ничего не было найдено
			'not_found_in_trash' => 'Не найдено в корзине', // если не было найдено в корзине
			'parent_item_colon'  => '', // для родителей (у древовидных типов)
			'menu_name'          => 'Тренеры', // название меню
		],
		'public'                 => true,
        'menu_icon'           => 'dashicons-superhero-alt',
		'supports'            => ['title'], // 'title','editor','author','thumbnail','excerpt','trackbacks','custom-fields','comments','revisions','page-attributes','post-formats'
		'has_archive'         => true,
	]);
    register_post_type('schedule', [
		'labels' => [
			'name'               => 'Занятия', // основное название для типа записи
			'singular_name'      => 'Занятие', // название для одной записи этого типа
			'add_new'            => 'Добавить новое занятие', // для добавления новой записи
			'add_new_item'       => 'Добавление занятия', // заголовка у вновь создаваемой записи в админ-панели.
			'edit_item'          => 'Редактирование занятия', // для редактирования типа записи
			'new_item'           => 'Новое занятие', // текст новой записи
			'view_item'          => 'Смотреть занятие', // для просмотра записи этого типа.
			'search_items'       => 'Искать занятие', // для поиска по этим типам записи
			'not_found'          => 'Не найдено', // если в результате поиска ничего не было найдено
			'not_found_in_trash' => 'Не найдено в корзине', // если не было найдено в корзине
			'parent_item_colon'  => '', // для родителей (у древовидных типов)
			'menu_name'          => 'Занятия', // название меню
		],
		'public'                 => true,
        'menu_icon'           => 'dashicons-schedule',
		'supports'            => ['title'], // 'title','editor','author','thumbnail','excerpt','trackbacks','custom-fields','comments','revisions','page-attributes','post-formats'
		'has_archive'         => true,
	]);
    register_post_type('prices', [
		'labels' => [
			'name'               => 'Списки цен', // основное название для типа записи
			'singular_name'      => 'Список цен', // название для одной записи этого типа
			'add_new'            => 'Добавить новый список цен', // для добавления новой записи
			'add_new_item'       => 'Добавление списка цен', // заголовка у вновь создаваемой записи в админ-панели.
			'edit_item'          => 'Редактирование списка цен', // для редактирования типа записи
			'new_item'           => 'Новый список цен', // текст новой записи
			'view_item'          => 'Смотреть список цен', // для просмотра записи этого типа.
			'search_items'       => 'Искать список цен', // для поиска по этим типам записи
			'not_found'          => 'Не найдено', // если в результате поиска ничего не было найдено
			'not_found_in_trash' => 'Не найдено в корзине', // если не было найдено в корзине
			'parent_item_colon'  => '', // для родителей (у древовидных типов)
			'menu_name'          => 'Списки цен', // название меню
		],
		'public'                 => true,
        'menu_icon'           => 'dashicons-money-alt',
        'show_in_rest'         => true, // нужно, чтобы включить guttenberg редактор
		'supports'            => ['title', 'editor'], // 'title','editor','author','thumbnail','excerpt','trackbacks','custom-fields','comments','revisions','page-attributes','post-formats'
		'has_archive'         => true,
	]);
    register_post_type('cards', [
		'labels' => [
			'name'               => 'Карты', // основное название для типа записи
			'singular_name'      => 'Карта', // название для одной записи этого типа
			'add_new'            => 'Добавить новую карту', // для добавления новой записи
			'add_new_item'       => 'Добавление карты', // заголовка у вновь создаваемой записи в админ-панели.
			'edit_item'          => 'Редактирование карты', // для редактирования типа записи
			'new_item'           => 'Новая карты', // текст новой записи
			'view_item'          => 'Смотреть карту', // для просмотра записи этого типа.
			'search_items'       => 'Искать карту', // для поиска по этим типам записи
			'not_found'          => 'Не найдено', // если в результате поиска ничего не было найдено
			'not_found_in_trash' => 'Не найдено в корзине', // если не было найдено в корзине
			'parent_item_colon'  => '', // для родителей (у древовидных типов)
			'menu_name'          => 'Клубные карты', // название меню
		],
		'public'                 => true,
        'menu_icon'           => 'dashicons-tickets-alt',
		'supports'            => ['title'], // 'title','editor','author','thumbnail','excerpt','trackbacks','custom-fields','comments','revisions','page-attributes','post-formats'
		'has_archive'         => false, // нет отдельной страницы archive-... будут выводиться как элемент на главной и странице цен
	]);
    register_post_type('orders', [
		'labels' => [
			'name'               => 'Заявки', // основное название для типа записи
			'singular_name'      => 'Заявка', // название для одной записи этого типа
			'add_new'            => 'Добавить новую заявку', // для добавления новой записи
			'add_new_item'       => 'Добавление заявки', // заголовка у вновь создаваемой записи в админ-панели.
			'edit_item'          => 'Редактирование заявки', // для редактирования типа записи
			'new_item'           => 'Новая заявка', // текст новой записи
			'view_item'          => 'Смотреть заявку', // для просмотра записи этого типа.
			'search_items'       => 'Искать заявку', // для поиска по этим типам записи
			'not_found'          => 'Не найдено', // если в результате поиска ничего не было найдено
			'not_found_in_trash' => 'Не найдено в корзине', // если не было найдено в корзине
			'parent_item_colon'  => '', // для родителей (у древовидных типов)
			'menu_name'          => 'Заявки', // название меню
		],
		'public'                 => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_icon'           => 'dashicons-phone',
		'supports'            => ['title'], // 'title','editor','author','thumbnail','excerpt','trackbacks','custom-fields','comments','revisions','page-attributes','post-formats'
		'has_archive'         => false,
	]);
}

register_taxonomy('schedule_days', ['schedule'], [
    'labels'                => [
        'name'              => 'Дни недели',
        'singular_name'     => 'День недели',
        'search_items'      => 'Найти день недели',
        'all_items'         => 'Все дни недели',
        'view_item '        => 'Посмотреть дни недели',
        'edit_item'         => 'Редактировать дни недели',
        'update_item'       => 'Обновить',
        'add_new_item'      => 'Добавить день недели',
        'new_item_name'     => 'Добавить день недели',
        'menu_name'         => 'Все дни недели',
    ],
    'description'           => '', // описание таксономии
    'public'                => true,
    'hierarchical'          => true, // удобней всегда делать иерархическими, типа рубрик
]);
register_taxonomy('places', ['schedule'], [
    'labels'                => [
        'name'              => 'Залы',
        'singular_name'     => 'Зал',
        'search_items'      => 'Найти зал',
        'all_items'         => 'Все залы',
        'view_item '        => 'Посмотреть зал',
        'edit_item'         => 'Редактировать зал',
        'update_item'       => 'Обновить',
        'add_new_item'      => 'Добавить зал',
        'new_item_name'     => 'Добавить зал',
        'menu_name'         => 'Все залы',
    ],
    'description'           => '', // описание таксономии
    'public'                => true,
    'hierarchical'          => true, // удобней всегда делать иерархическими, типа рубрик
]);


function si_paste_link($atts) {
    $params = shortcode_atts([
        'link' => '',
        'text' => '',
        'type' => 'link',
    ], $atts);
    $params['text'] = $params['text'] ? $params['text'] : $params['link'];
    if ($params['link']) {
        $protocol = '';
        switch ($params['type']) {
            case 'email':
                $protocol = 'mailto:';
                break;
            case 'phone':
                $protocol = 'tel:';
                $params['link'] = preg_replace('/[^+0-9]/', '', $params['link']);
                break;
            default: $protocol = '';
        }
        $link = $protocol . $params['link'];
        $text = $params['text'];
        return "<a href=\"$link\">$text</a>";
    } else return '';
}

function si_meta_boxes() {
    add_meta_box(
        'si-like',
        'Количество лайков: ',
        'si_meta_like_cb',
        'post',
    );
    $fields = [
        'si_order_date' => 'Дата заявки: ',
        'si_order_name' => 'Имя клиента: ',
        'si_order_phone' => 'Номер телефона: ',
        'si_order_choice' => 'Выбор клиента: ',
    ];
    foreach ($fields as $slug => $text) {
        add_meta_box(
            $slug,
            $text,
            'si_order_fields_cb', // вызывается функция
            'orders',
            'advanced',
            'default',
            $slug,
        );
    }
}
// function si_meta_like_cb ($post_obj) {
//     $likes = get_post_meta($post_obj->ID, 'si-like', true);
//     $likes = $likes ? $likes : 0;
//     // echo "<input type='text' name='si-like' value='{$likes}'>";
//     echo "<p>{$likes}</p>";
// }

function si_order_fields_cb($post_obj, $slug) {
    $slug = $slug['args'];
    $data = '';
    switch ($slug) {
        case 'si_order_date':
            $data = $post_obj->post_date;
            break;
        case 'si_order_choice':
            $id = get_post_meta($post_obj->ID, $slug, true);
            $title = get_the_title($id);
            $type = get_post_type_object(get_post_type($id))->labels->name; // получает название записи: карты, тренеры, услуги и т.д.
            $data = 'Клиент выбрал: <strong>' . $title. '</strong><br>Из раздела: <strong>' . $type . '</strong>';
            break;
        default: 
            $data = get_post_meta($post_obj->ID, $slug, true);
            $data = $data ? $data : 'Нет данных';
    }

    echo "<p>{$data}</p>";
}

function si_save_like_meta($post_id) {
    if (isset($_POST['si-like'])) {
        update_post_meta($post_id, 'si-like', $_POST['si-like']);
    }
}

function si_modal_form_handler() {
    $name = $_POST['si-user-name'] ? $_POST['si-user-name'] : 'empty';
    $phone = $_POST['si-user-phone'] ? $_POST['si-user-phone'] : false;
    $choice = $_POST['form-post-id'] ? $_POST['form-post-id'] : 'empty';
    if ($phone) { // не сохранять заявку, если нет номера телефона
       $name = wp_strip_all_tags($name); // удаляет все html и js теги для безопасности
       $phone = wp_strip_all_tags($phone);
       $choice = wp_strip_all_tags($choice);
       $id = wp_insert_post(wp_slash([
            'post_title' => 'Заявка № ',
            'post_type' => 'orders',
            'post_status' => 'publish',
            'meta_input' => [
                'si_order_name' => $name,
                'si_order_phone' => $phone,
                'si_order_choice' => $choice,
            ],
       ])); // создает новую запись, без админки,  возвращает id созданной записи, 0 в случае ошибки. wp_slash экранирует все специальные символы
       if ($id !== 0) {
            wp_update_post([
                'ID' => $id,
                'post_title' => 'Заявка № ' . $id // добавляет номер записи
            ]);
            update_field('orders_status', 'new', $id); // устанавливает поле созданное с помощью ACF, новая заявка
            // wp_mail(); // здесь можно добавить отправку email с заявкой
       }
    }
    header('Location: ' . home_url()); // возвращает на главную страницу
}

function si_likes() {
    $id = $_POST['id']; // данные из формы
    $todo = $_POST['todo']; // данные из формы
    $current_data = get_post_meta($id, 'si-like', true);
    $current_data = $current_data ? $current_data : 0;
    if ($todo === 'plus') {
        $current_data++;
    } else {
        $current_data--;
    }
    $res = update_post_meta($id, 'si-like', $current_data);
    if ($res) {
        echo $current_data;
        wp_die();
    } else {
        wp_die('Лайк не сохранился, ошибка на сервере. Попробуйте ещё раз', 500);
    }
}

function si_like_column($col_name, $id) {
    if ($col_name !== 'col_likes') return;
    $likes = get_post_meta($id, 'si-like', true);
    echo $likes ? $likes : 0;
}

function si_add_col_likes($defaults) {
    $type = get_current_screen();
    if ($type->post_type === 'post') { // проверка, что страница с записями, чтобы везде не выводилась колонка с лайками
        $defaults['col_likes'] = 'Лайки';
    }
    return $defaults;
}

function si_order_column($col_name, $id) {
    if ($col_name !== 'col_order_status') return;
    $order_status = get_field('orders_status', $id); // получает значение статуса заявки из поля ACF
    echo $order_status["label"] ? $order_status["label"] : 'Не установлен'; // выводит значение. массив с ключом потому что у статуса заявок в ACF возвращаемое значение массив
}

function si_add_col_orders($defaults) { // регистрирует колонку со слагом col_order_status
    $type = get_current_screen();
    if ($type->post_type === 'orders') { // проверка, что страница с заявками, чтобы везде не выводилась колонка с лайками
        $defaults['col_order_status'] = 'Статус заявки';
    }
    return $defaults;
}

function _si_assets_path($path) {
    return get_template_directory_uri() . '/assets/' . $path;
}


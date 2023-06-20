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
add_action('save_post', 'si_save_like_meta');

add_shortcode('si-paste-link', 'si_paste_link'); // добавляет шорткод [si-paste-link], когда его находит в тексте, вызывает функцию si_paste_link

// add_filter('show_admin_bar', '__return_false'); // убирает верхнюю панель администратора на странице
add_filter('si_widget_text_filter', 'do_shortcode'); // фильтр c именем для si_widget_text_filter, вызывает встроенную функцию do_shortcode, которая добавляет поддержку шорткодов в тексте


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
			'singular_name'      => 'Услуги', // название для одной записи этого типа
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
			'singular_name'      => 'Тренеры', // название для одной записи этого типа
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
			'singular_name'      => 'Занятия', // название для одной записи этого типа
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
			'name'               => 'Цены', // основное название для типа записи
			'singular_name'      => 'Цены', // название для одной записи этого типа
			'add_new'            => 'Добавить новую цену', // для добавления новой записи
			'add_new_item'       => 'Добавление цены', // заголовка у вновь создаваемой записи в админ-панели.
			'edit_item'          => 'Редактирование цены', // для редактирования типа записи
			'new_item'           => 'Новая цена', // текст новой записи
			'view_item'          => 'Смотреть цену', // для просмотра записи этого типа.
			'search_items'       => 'Искать цены', // для поиска по этим типам записи
			'not_found'          => 'Не найдено', // если в результате поиска ничего не было найдено
			'not_found_in_trash' => 'Не найдено в корзине', // если не было найдено в корзине
			'parent_item_colon'  => '', // для родителей (у древовидных типов)
			'menu_name'          => 'Цены', // название меню
		],
		'public'                 => true,
        'menu_icon'           => 'dashicons-money-alt',
		'supports'            => ['title'], // 'title','editor','author','thumbnail','excerpt','trackbacks','custom-fields','comments','revisions','page-attributes','post-formats'
		'has_archive'         => true,
	]);
    register_post_type('cards', [
		'labels' => [
			'name'               => 'Карты', // основное название для типа записи
			'singular_name'      => 'Карты', // название для одной записи этого типа
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
}

register_taxonomy('shedule_days', ['schedule'], [
    'labels'                => [
        'name'              => 'Дни недели',
        'singular_name'     => 'Дни недели',
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
        'singular_name'     => 'Залы',
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
        'Количество лайков:',
        'si_meta_like_cb',
        'post',
    );
}
function si_meta_like_cb ($post_obj) {
    $likes = get_post_meta($post_obj->ID, 'si-like', true);
    $likes = $likes ? $likes : 0;
    echo "<input type='text' name='si-like' value='{$likes}'>";
    // echo "<p>{$likes}</p>";
}

function si_save_like_meta($post_id) {
    if (isset($_POST['si-like'])) {
        update_post_meta($post_id, 'si-like', $_POST['si-like']);
    }
}


function _si_assets_path($path) {
    return get_template_directory_uri() . '/assets/' . $path;
}


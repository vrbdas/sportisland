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
add_action('init', 'si_register_types');

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
}

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

function _si_assets_path($path) {
    return get_template_directory_uri() . '/assets/' . $path;
}
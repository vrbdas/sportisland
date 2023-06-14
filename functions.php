<?php 

$widgets = [
    'widget-text.php',
    'widget-contacts.php',
    'widget-email.php',
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

// add_filter('show_admin_bar', '__return_false'); // убирает верхнюю панель администратора на странице

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
    register_widget('si_widget_email');
    register_widget('si_widget_social');
    register_widget('si_widget_iframe');
    register_widget('si_widget_info');
}

function _si_assets_path($path) {
    return get_template_directory_uri() . '/assets/' . $path;
}
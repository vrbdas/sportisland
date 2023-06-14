<?php

class SI_Widget_Iframe extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'si_widget_iframe',
            'SportIsland - Iframe',
            [
                'name' => 'SportIsland - Iframe',
                'description' => 'Выводит Iframe. Нужен для встраивания видео, карт и т.д.',
            ]
        );
    }

    public function form($instance)
    {
?>
    <p>
        <label for="<?= $this->get_field_id('id-code') ?>">Введите текст:</label>
        <textarea 
            id="<?= $this->get_field_id('id-code') ?>" 
            name="<?= $this->get_field_name('code') ?>" 
            value="<?= esc_html($instance['code']) ?>"
            class="widefat"
        >
            <?= esc_html($instance['code']) ?>
        </textarea>
    </p>
<?php
    }

    public function widget($args, $instance)
    {
        echo $instance['code'];
    }

    public function update($new_instance, $old_instance)
    {
        return $new_instance;
    }
}
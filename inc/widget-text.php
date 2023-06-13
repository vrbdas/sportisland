<?php

class SI_Widget_Text extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'si_widget_text',
            'SportIsland - Текстовый виджет',
            [
                'name' => 'SportIsland - Текстовый виджет',
                'description' => 'Выводит простой текст без верстки',
            ]
        );
    }

    public function form($instance)
    {
?>
    <p>
        <label for="<?= $this->get_field_id('id-text') ?>">Введите текст:</label>
        <input 
            id="<?= $this->get_field_id('id-text') ?>" 
            type="text" 
            name="<?= $this->get_field_name('text') ?>" 
            value="<?= $instance['text'] ?>">
    </p>
<?php
    }

    public function widget($args, $instance)
    {
        echo $instance['text'];
    }

    public function update($new_instance, $old_instance)
    {
        return $new_instance;
    }
}
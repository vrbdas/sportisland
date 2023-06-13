<?php

class SI_Widget_Email extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'si_widget_email',
            'SportIsland - Виджет email адреса',
            [
                'name' => 'SportIsland - Виджет email адреса',
                'description' => 'Выводит email адрес в виде ссылки',
            ]
        );
    }

    public function form($instance)
    {
?>
    <p>
        <label for="<?= $this->get_field_id('id-email') ?>">Введите email:</label>
        <input 
            id="<?= $this->get_field_id('id-email') ?>" 
            type="text" 
            name="<?= $this->get_field_name('email') ?>" 
            value="<?= $instance['email'] ?>">
    </p>
<?php
    }

    public function widget($args, $instance)
    {
        // $tel = preg_replace('/[^+0-9]/', '', $instance['phone']);

?>
    <a href="mailto:<?= $instance['email'] ?>"><?= $instance['email'] ?></a>
<?php
    }

    public function update($new_instance, $old_instance)
    {
        return $new_instance;
    }
}
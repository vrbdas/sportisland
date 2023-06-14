<?php

class SI_Widget_Info extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'si_widget_info',
            'SportIsland - Информация',
            [
                'name' => 'SportIsland - Информация',
                'description' => 'Выводит инфомацию о спортклубе. Основное использование - на странице контактов под картой',
            ]
        );
    }

    public function form($instance)
    {
        $icons = [
            'address' => 'Адрес',
            'time' => 'Время',
            'phone' => 'Телефон',
            'email' => 'Email',
        ];
?>
    <p>
        <label for="<?= $this->get_field_id('id-info') ?>">Текст:</label>
        <input 
            id="<?= $this->get_field_id('id-info') ?>" 
            type="text" 
            name="<?= $this->get_field_name('info') ?>" 
            value="<?= $instance['info'] ?>">
    </p>
    <p>
        <label for="<?= $this->get_field_id('id-info-icon') ?>">Тип записи:</label>
        <select name="<?= $this->get_field_name('info-icon') ?>" id="<?= $this->get_field_id('id-info-icon') ?>">
<?php 
    foreach ($icons as $key => $item):
        ?>
            <option value="<?= $key ?>" <?php selected($instance['info-icon'], $key, true); ?>>
                <?= $item ?>
            </option>
        <?php
    endforeach;
?>
        </select>
    </p>
<?php
    }

    public function widget($args, $instance)
    {
        switch ($instance['info-icon']) {
            case 'address':
?>
                <span class="widget-address"> <?= $instance['info'] ?> </span>
<?php
                break;
            case 'time':
?>
                <span class="widget-working-time"> <?= $instance['info'] ?> </span>
<?php
                break;
            case 'phone':
?>
                <a href="tel:<?= preg_replace('/[^+0-9]/', '', $instance['info']) ?>" class="widget-phone"> <?= $instance['info'] ?> </a>
<?php
                break;
            case 'email':
?>
                <a href="mailto:<?= $instance['info'] ?>" class="widget-email"> <?= $instance['info'] ?> </a>
<?php
                break;
            default: echo '';
        }
    }

    public function update($new_instance, $old_instance)
    {
        return $new_instance;
    }
}
<?php

class SI_Widget_Contacts extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'si_widget_contacts',
            'SportIsland - Виджет контактов',
            [
                'name' => 'SportIsland - Виджет контактов',
                'description' => 'Выводит номер телефона и адрес',
            ]
        );
    }

    public function form($instance)
    {
?>
    <p>
        <label for="<?= $this->get_field_id('id-phone') ?>">Введите номер телефона:</label>
        <input 
            id="<?= $this->get_field_id('id-phone') ?>" 
            type="text" 
            name="<?= $this->get_field_name('phone') ?>" 
            value="<?= $instance['phone'] ?>">
    </p>
    <p>
        <label for="<?= $this->get_field_id('id-address') ?>">Введите адрес:</label>
        <input 
            id="<?= $this->get_field_id('id-address') ?>" 
            type="text" 
            name="<?= $this->get_field_name('address') ?>" 
            value="<?= $instance['address'] ?>">
    </p>
<?php
    }

    public function widget($args, $instance)
    {
        $tel = preg_replace('/[^+0-9]/', '', $instance['phone']);

?>
        <address class="main-header__widget widget-contacts">
            <a href="<?= $tel ?>" class="widget-contacts__phone"> <?= $instance['phone'] ?> </a>
            <p class="widget-contacts__address"> <?= $instance['address'] ?> </p>
        </address>
<?php
    }

    public function update($new_instance, $old_instance)
    {
        return $new_instance;
    }
}
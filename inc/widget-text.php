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
    // функция отвечает за форму заполнения данных виджета в админке. тег form и кнопка сохранить добавляются автоматически
    // instance это массив с данными из формы, который записан в базе данных, нужно добавить как параметр функции для того, чтобы они появились в форме при обновлении страницы
    {
?>
    <p>
        <label for="<?= $this->get_field_id('id-text') ?>">Введите текст:</label>
        <textarea 
            id="<?= $this->get_field_id('id-text') ?>" 
            type="text" 
            name="<?= $this->get_field_name('text') // text это будет ключ в массиве instance с данными из input?>" 
            value="<?= $instance['text'] ?>"
            class="widefat"
        >
            <?= $instance['text'] ?>
        </textarea>
    </p>
<?php
    }

    public function widget($args, $instance) { // функция отвечает за вывод данных на страницу
        echo apply_filters('si_widget_text_filter', $instance['text']);  // выводит текст. apply_filters вызывает фильтр, который добавляет поддержку шорткодов
    }

    public function update($new_instance, $old_instance) // отвечает за сохранение данных, можно прописать какие-то проверки
    {
        return $new_instance;
    }
}
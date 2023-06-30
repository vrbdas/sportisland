<?php get_header(); ?>

    <main class="main-content">
      <div class="wrapper">
        <?php get_template_part('templates/breadcrumbs') ?>
      </div>
      <?php
            if(have_posts()):
              while (have_posts()):
                the_post();
        ?>
      <article class="main-article wrapper">
        <header class="main-article__header">
          <?php 
            $custom_thumb = get_field('post_si_thumb'); // массив с данными картинки из ACF
            if ($custom_thumb) {
              $url = $custom_thumb['url'];
              $alt = $custom_thumb['alt'];
              echo "<img src='$url' alt='$alt' class='main-article__thumb'>";
            } else {
              the_post_thumbnail('full', ['class' => 'main-article__thumb']); 
            }
          ?>
          <h1 class="main-article__h"><?php the_title(); ?></h1>
        </header>
        <?php the_content(); ?>
        <footer class="main-article__footer">
          <time datetime="<?= get_the_date('Y-m-d') ?>"><?= get_the_date('j F Y') ?></time>
          <button class="main-article__like like" data-href="<?php echo esc_url(admin_url('admin-ajax.php'))?>" data-id="<?php echo $id; ?>">
            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 51.997 51.997" style="enable-background:new 0 0 51.997 51.997;" xml:space="preserve">
              <style> path{fill: #666;}</style>
              <path d="M51.911,16.242C51.152,7.888,45.239,1.827,37.839,1.827c-4.93,0-9.444,2.653-11.984,6.905
                c-2.517-4.307-6.846-6.906-11.697-6.906c-7.399,0-13.313,6.061-14.071,14.415c-0.06,0.369-0.306,2.311,0.442,5.478
                c1.078,4.568,3.568,8.723,7.199,12.013l18.115,16.439l18.426-16.438c3.631-3.291,6.121-7.445,7.199-12.014
                C52.216,18.553,51.97,16.611,51.911,16.242z" />
            </svg>
            <span class="like__text">Нравится </span>
            <span class="like__count">
              <?php
                $likes = get_post_meta($id, 'si-like', true);
                echo $likes ? $likes : 0;
              ?>
            </span>
          </button>
          <script>
            window.addEventListener('load', () => {
              const likeBtn = document.querySelector('.like');
              const postID = likeBtn.getAttribute('data-id');
              try {
                if (!localStorage.getItem('liked')) {
                localStorage.setItem('liked', ''); // если в ls пусто, то устанавливает значение liked равно пустая строка
              }
              } catch (error) {
                console.log(error);
              }
              function getAboutLike(id) {
                let hasLike = false;
                try {
                  hasLike = localStorage.getItem('liked').split(',').includes(id); // проверяет, если ли id этой страницы в local storage. если есть, значит лайк уже поставили
                } catch (error) {
                  console.log(error);
                }
                return hasLike;
              }
              let hasLike = getAboutLike(postID);
              if(hasLike) {
                likeBtn.classList.add('like_liked'); // если лайк уже есть, меняет цвет
              }
              likeBtn.addEventListener('click', (e) => {
                e.preventDefault();
                likeBtn.setAttribute('disabled', '') // предотвращает быстрые клики по кнопке
                let hasLike = getAboutLike(postID);
                const data = new FormData();
                data.append('action', 'post-likes'); // обязательно добавить action, его значение дописать в add_action в functions.php
                let todo = hasLike ? 'minus' : 'plus'; // если лайк уже есть, то минус, если нет, то плюс
                data.append('todo', todo); // добавляет в данные формы plus или minus
                data.append('id', postID); // добавляет в данные формы id страницы, на которой кликнули лайк
                postData(likeBtn.getAttribute('data-href'), data) // Настраивает и посылает запрос на сервер
                .then((result) => { // Обработка успешного promise
                  likeBtn.querySelector('.like__count').textContent = result; // записать ответ от сервера как значение поля с кол-вом лайков
                  let localData = localStorage.getItem('liked'); // значение лайкнуто или нет в localStorage
                  let newData = '';
                  if (hasLike) { // если лайк уже есть
                    newData = localData.split(',').filter(el => el !== postID).join(','); // оставляет все id, кроме id страницы, на которой поставили лайк. строку сначала превращает в массив, фильтрует, потом обратно в строку
                  } else { // если лайка нет
                    newData = localData.split(',').filter(el => el !== '').concat(postID).join(','); // превращает в массив и дополнительно фильтрует убирая пустые строки. добавляет id страницы, на которой поставили лайк. превращает массив обратно в строку
                  }
                  localStorage.setItem('liked', newData);
                  likeBtn.classList.toggle('like_liked');
                })
                .catch(() => { // Обработка reject (ошибки)
                  console.log(`Ошибка соединения с сервером`);
                })
                .finally (() => { // выполнится в любом случае
                  likeBtn.removeAttribute('disabled', ''); // возвращает возможность нажимать кнопку
                })
            });
          })
            async function postData(url, data) { // Настраивает и посылает запрос на сервер
            const result = await fetch(url, { // await дождется результата функции fetch
              method: 'POST', // POST это отправка, GET получение
              body: data, // Тело запроса, если запрос GET, то не нужно
            });
            return await result.text(); // Ответ от сервера в виде PROMISE
          }
          </script>
        </footer>
      </article>
      <?php 
        endwhile;
        endif;
      ?>
    </main>

<?php get_footer(); ?>
<?php

// добавили поддержку картинок к постам
add_theme_support('post-thumbnails');

//очистка html img от предуставновленных размеров
add_filter('post_thumbnail_html', 'remove_width_attribute', 10);
add_filter('image_send_to_editor', 'remove_width_attribute', 10);
function remove_width_attribute($html){
	$html = preg_replace('/(width|height)="\d*"\s/' , "" , $html);
	return $html;
}

// -------- САЙДБАР ---------------------------------------------

// зарегистрируем сайдбар в который будем вкладывать наши виджеты

$left_sb= array('name'=>'left sidebar',
                 'id'=>'left_s',
                 'description'=>'Это левый сайдбар',
                 'before_widget'=>'<div class = "wrap-left_sb">',
                 'after_widget'=>'</div>');

register_sidebar($left_sb); // принимает аргумент - переменную в которой массив описанными ключами




//------- СОЗДАНИЕ И РЕГИСТРАЦИЯ ВИДЖЕТА ---------------------------------
// регистрируем виджет со своим именем
function register_my_widget(){
  register_widget('Game');
  // параметр - свое имя виджета
}

// создаем виджет  
add_action( 'widgets_init', 'register_my_widget' );
// первый параметр инициализация виджета
// второй парамент имя функции в которой мы зарегистрировали виджет





// --------КОНСТРУКТОР НАШЕГО ВИДЖЕТА ------------------------------------

// GAME это имя того виджета который зарегистрировали выше
// extends WP_Widget - означает что мы расширяем предустановленый класс WP_Widget своим кастомным виджетом Game
class Game extends WP_Widget {
  
  
  
//функция ---- КОНСТРУИРОВАНИЯ---- виджета
function Game(){
//$opisanie - описание данного виджета в Админ панели
//$control - размеры виджета. отображается в Админ панели
//$name - имя виджета в Админ панели 
$opisanie = array('description' => 'Виджет, который выводит последние записи');
$control = array( 'width' => 300, 'height' => 350, 'id_base' => 'game' );
$name = 'ПОСЛЕДНИЕ';
 
// после того как сконструировали виджет нужно ПРИМЕНИТЬ эти настройки
// данный код говорит , примени все это к кастомному виджету в предустановленном классе.
//Аргументы по порядку 
//1-имя зарегистрированного выше вилджета 
//2-имя виджета в Адм панели 
//3-описание выджета
//4-размеры 
$this->WP_Widget( 'game', $name,$opisanie, $control); 
 
}
  
  
  
//------ АДМИНИСТРАТИВНАЯ ЧАСТЬ ВИДЖЕТА --------------------------------------
//в этой функции мы конструируем из чего будет состоять наш виджет, т.е. какие поля в нем будут и для каких целей  
  
function form( $instance ) {?>

<!--
в переменную $instanse мы заносим МАССИВ с ключамии
ключи получаем так: полю значение которого нам нужно получить даем ИМЯ КЛЮЧА 
и далее с помощью конструкции связываем КЛЮЧ => полученное ЗНАЧЕНИЕ

$this->get_field_id('имя ключа') 
$this->get_field_name('имя ключа')

$instance['имя ключа'] - заносим в ПЕРЕМЕННУЮ ПАРУ КЛЮЧ-ЗНАЧЕНИЕ   
    	-->
			
<!-- в данном случае это поле служит заголовком виджета, который будет отображаться во фронтальной части -->
 <p> 
		<label for="<?= $this->get_field_id('title'); ?>">ЗАГОЛОВОК ПОЛЯ</label> 
		<input 
						type="text" 
						id="<?= $this->get_field_id('title'); ?>" 
						name="<?= $this->get_field_name('title'); ?>" 
						value="<?= $instance['title']; ?>"
						style="width:100%"
		 > 
	</p>
<!--	это поле в котором мы указываем виджету сколько постов выводить в виджете
результатом будет созданный ключ SIZE со значением который мы укажем и. далее это значение мы будем подставлять в запрос на выборку	-->
	<p> 
		<label for="<?= $this->get_field_id('size'); ?>">
					КОЛИЧЕСТВО ПОСТОВ НА ВЫВОД
		</label>
			
		<input 
						type="text" 
						id="<?= $this->get_field_id('size'); ?>" 
						name="<?= $this->get_field_name('size'); ?>" 
						value="<?= $instance['size']; ?>"
						style="width:10%"
		 >
		 
	</p> 
<!--	переключатель стилей. 
выбранному OPTION будет соответствовать определенный класс указанный в VALUE. 
данный класс получит ключ VID который мы прописали в атрибуте NAME тега SELECT -->
	<p>
		<select 
		name="<?= $this->get_field_name('vid'); ?>" 
		id="<?= $this->get_field_id('vid'); ?>"
		> 
			<option value="class_green">green_Style</option>
			<option value="class_red">red_Style</option>
			<option value="class_blue">blue_style</option> 
		</select>
	</p>
	
<?php 
  }
  
  
  
// ---------------РАСПАКОВКА полученных параметров из виджета -------------  
function update( $new_instance, $old_instance ) {

 $instance = $old_instance;
 // strip_tags() очищает строку от символов
 $instance['title'] = strip_tags($new_instance['title']); 
 $instance['size'] = strip_tags($new_instance['size']);
 $instance['vid'] = strip_tags($new_instance['vid']); 
		
 return $instance;
	
	}
	 
  
  
  
// ----------- ФРОНТ ----------------------------------------------
//Вывод виджета во Фронт-энде 
//$args     аргументы виджета.
//$instance сохраненные данные которые мы получили из ВИДЖЕТА
	 
function widget( $args, $instance ) {
  
 $title = apply_filters( 'widget_title', $instance['title'] );
 $size = $instance['size'];
 $class= $instance['vid'];
 $query = new WP_Query(array('post_type'=>'post', 
                             'order_by' => 'date',
                             'posts_per_page'=> $size) );
  

		echo $args['before_widget']; // открыли виджет
  
		if ( ! empty( $title ) ) {// если не пустой заголовок
		  echo $args['before_title'] . $title . $args['after_title'];
		}?> 
  <ul>
<!--  создадим ЦИКЛ который будет выводить столько записей, сколько указано в запросе -->
<?php
while($query -> have_posts()){ // проверяем есть ли посты в составленном запосе
		  $query -> the_post(); ?> <!--получаем первый пакет   --> 
		  
  <li><?php the_title(); ?> </li><!-- выведем заголовок поста из полученного пакета--> 
      
<?php } ?><!--закрыли цикл-->

 </ul> <!-- закрыли обертку-->
<?php  
  
echo $args['after_widget']; // закрыли виджет
  
	} // закрыли фукнкцию вывода во ФРОНТЕ

  
  
  
  
 
 
}//end class

 
?>
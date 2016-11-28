<?php

add_theme_support('post-thumbnails');


//sidebar

$sb=array(
												'name'=>'Сайдбар',
												'id'=>'left_sidebar',
												'description'=>'some_description',
												'before_widget'=>'<div class=block menu>',
												'after_widget'=>'</div>',
												'before_title'=>'<h3>',
												'after_title'=>'</h3>');

register_sidebar($sb);								


//очистка html img 
add_filter('post_thumbnail_html', 'remove_width_attribute', 10);
add_filter('image_send_to_editor', 'remove_width_attribute', 10);
function remove_width_attribute($html){
	$html = preg_replace('/(width|height)="\d*"\s/' , "" , $html);
	return $html;
}



// ======= DISABLE STANDART WIDGETS ++++----------

function delete_Widgets(){
	
unregister_widget('WP_Widget_Pages');            // Виджет страниц
unregister_widget('WP_Widget_Calendar');         // Календарь
unregister_widget('WP_Widget_Archives');         // Архивы
unregister_widget('WP_Widget_Links');            // Ссылки
unregister_widget('WP_Widget_Meta');             // Мета виджет
unregister_widget('WP_Widget_Search');           // Поиск
unregister_widget('WP_Widget_Text');             // Текст
unregister_widget('WP_Widget_Categories');       // Категории
unregister_widget('WP_Widget_Recent_Posts');     // Последние записи
unregister_widget('WP_Widget_Recent_Comments');  // Последние комментарии
unregister_widget('WP_Widget_RSS');              // RSS
unregister_widget('WP_Widget_Tag_Cloud');        // Облако меток
unregister_widget('WP_Nav_Menu_Widget');         // Меню
	

}

// выполнили отключение виджетов
add_action('widgets_init', 'delete_Widgets'); 

 

// Класс виджета
class My_Widget extends WP_Widget {

	
	
	
	function My_Widget() {
		// Запускаем родительский класс
		parent::__construct( '', 'Мой виджет', array('description'=>'виджет вывода последних записей блога с миниатюрами каждой из них') );
	}

	
	
	
	
	// Вывод виджета
	function widget( $args, $instance ){
		$title = apply_filters( 'widget_title', $instance['title'] );
		$size = $instance['size'];
		$class= $instance['vid'];
		$query = new WP_Query(array('post_type'=>'post', 
																											  'order_by' => 'date',
																											  'posts_per_page'=> $size) );

echo $args['before_widget'];

						if( $title )
									echo $args['before_title'] . $title . $args['after_title']; ?>


						<ul class="<?= $class ?>">

						 <?php	while($query -> have_posts()){ 
													$query -> the_post(); ?> 
								
									<li>
											<div class="widget">
													<a href="<? the_permalink();?>"><?php the_post_thumbnail(); ?></a>
											</div>
											<h3 class="widget_h">
													<?php the_title(); ?>
											</h3>
									</li>


						<?php }?>

						</ul>	

<?php 
echo $args['after_widget'];
	}

	
	
	
	
	
	
	// Сохранение настроек виджета (очистка)
	function update( $new_instance, $old_instance ) {

						$instance = $old_instance;
						// strip_tags() очищает строку от символов
						$instance['title'] = strip_tags($new_instance['title']); 
						$instance['size'] = strip_tags($new_instance['size']);
						$instance['vid'] = strip_tags($new_instance['vid']); 
		
						return $instance;
	
	}
	

	
	
	
	
	// html форма настроек виджета в Админ-панели
	function form( $instance ) {?>
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
	<p>
		<select name="<?= $this->get_field_name('vid'); ?>" id="<?= $this->get_field_id('vid'); ?>">
		
			<option value="class_green">green_Style</option>
			<option value="class_red">red_Style</option>
			<option value="class_blue">blue_style</option>
			
		</select>
	</p>
	<?php }
}






// Регистрация класса виджета
add_action( 'widgets_init', 'my_register_widgets' );
function my_register_widgets() {
	register_widget( 'My_Widget' );
}

?>
<?php get_header(); ?> 
<?php get_sidebar(); ?> 
     
                          
 <div class="right col-lg-9 col-md-9 col-sm-9">
             
 <?php if(have_posts()): while(have_posts()): the_post(); ?>
             
             
 <article>
                 
 
  <?php if(has_post_thumbnail() ){?>
	 
				<div class="photo"> 
						<?php the_post_thumbnail(); ?>
						
						<div class="date">
									<span class="day"><?php the_time('d'); ?> </span>
									<span class="mon"><?php the_time('M'); ?> </span>
						</div>
				</div>
          
<?php } ?>

	

 
			
 
        
 
                  
    <div class="text"> 
						<h1>       
									<a href="<?php the_permalink(); ?>"> <?php the_title(); ?> </a> 
						</h1> 
						<p><?php the_content(); ?></p>
    </div>
                  
    <div class="more">
  							<a href="<?php the_permalink(); ?>">Читать полностью</a>
    </div> 
                  
 </article>
              
 <?php endwhile; else : ?>
              
 <i>Записей пока нет !!</i>
      				
 <?php endif; ?>
                       
 </div>
          
                   
 <?php get_footer(); ?>
      
      				
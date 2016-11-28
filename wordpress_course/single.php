<?php get_header(); ?> 
 
     
                          
 <div class="col-lg-12 col-md-12 col-sm-12">
             
<?php if(have_posts()): while(have_posts()): the_post(); ?>
             
             
 <article>
                 

<?php if(has_post_thumbnail() ){?>
	 
				<div class="photo"> 
						<?php the_post_thumbnail(); ?>
				</div>
          
<?php } ?>

                  
    <div class="text"> 
          <h1>       
            <?php the_title(); ?>  
          </h1> 
          <p><?php the_content(); ?></p>
    </div>
    
                  
 </article>
              
 <?php endwhile; else : ?>
              
 <i>Записей пока нет !!</i>
      				
 <?php endif; ?>
                       
 </div>
          
                   
 <?php get_footer(); ?>
      
      				
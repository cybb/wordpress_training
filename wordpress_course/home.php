<?php get_header(); ?> 
<?php get_sidebar(); ?> 
  
<div class="left col-lg-10 col-md-10 col-sm-10">	 
    
<?php

$query = new WP_Query(array('post_type'=>'post', 
																											 'order_by' => 'date',
																											 'post_per_page'=> 8));

while($query -> have_posts()){
	
	$query -> the_post(); ?> 
                   
 <div class="news"> 
				<div class="col-md-6 wrap">
					 <a href="<? the_permalink();?>">
					 	<div class="pic">
							<?php the_post_thumbnail(); ?>
						</div>
						</a>
					
						
						<div class="descr">
							 <? the_title(); ?>
						</div>
						
				</div> 
 </div> 
	
<?php } ?>







</div>  
        
           
                 
              
 <?php get_footer(); ?>
      
      				
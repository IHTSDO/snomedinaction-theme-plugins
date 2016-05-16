<?php
/**
 * The template for displaying 404 pages (Not Found)
 *
 */
	get_header();
?>
	<div class="col-md-12"> 
        <!-- Row -->
        <div class="row"> 
            <div class="page-not-found">
                <h2><i class="icon-exclamation"></i><span><?php _e('404','dir');?> </span> </h2>
                <div class="cs-content404">
                  <div class="desc">
                    <p><?php _e('Apologies!!.. We are really working hard towards to fix the problem!!.. We will be get back you soon!!..
                    Keep in touch!!!..','dir');?> </p>
                  </div>
                  <a class="go-home cs-color" href="<?php echo esc_url(site_url()); ?>"><?php _e('Go Back To Home','dir');?> </a> </div>
                 <?php get_search_form(); ?>
            </div>
        </div>
        <!-- Row --> 
    </div>
<?php get_footer();?>
<?php 
/**
 * The template for displaying Search Form
 */
global $cs_theme_options
?>
<div class="cs-search-area">
    <form method="get" action="<?php echo esc_url(home_url()); ?>" role="search">
        <input type="text" class="form-control" onfocus="if(this.value =='<?php  _e('Enter Title, Keyword, Company','dir');?>') { this.value = ''; }" onblur="if(this.value == '') { this.value ='<?php  _e('Enter Title, Keyword, Company','dir');?>'; }" value="<?php  _e('Enter Title, Keyword, Company','dir');?>" name="s" id="s">
        <label>
            <input type="submit" class="btn cs-bgcolorhover" value="Search">
        </label>
    </form>
</div>
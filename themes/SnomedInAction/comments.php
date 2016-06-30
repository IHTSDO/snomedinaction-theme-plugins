<?php
/**
 * The template for displaying Comment form
 */ 
    global $cs_theme_options;
    if ( comments_open() ) {
        if ( post_password_required() ) return;
    }   
    if ( have_comments() ) : 
	?>
     <div id="comment">
        <div class="cs-section-title"><h4><?php echo comments_number(__('No Comments', 'dir'), __('Comment', 'dir'), __('Comments', 'dir') );?></h4></div>
        <ul>
            <?php wp_list_comments( array( 'callback' => 'cs_comment' ) );    ?>
        </ul>
        <?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
            <div class="navigation">
                <div class="nav-previous"><span class="meta-nav">&larr;</span><?php previous_comments_link( __( 'Older Comments', 'dir') ); ?></div>
                <div class="nav-next"><span class="meta-nav">&rarr;</span><?php next_comments_link( __( 'Newer Comments', 'dir') ); ?></div>
				
				
				
            </div> <!-- .navigation -->
        <?php endif; // check for comment navigation ?>        
        <?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
            <div class="navigation">
                <div class="nav-previous"><span class="meta-nav">&larr;</span><?php previous_comments_link( __( 'Older Comments', 'dir') ); ?></div>
                <div class="nav-next"><span class="meta-nav">&rarr;</span><?php next_comments_link( __( 'Newer Comments', 'dir') ); ?></div>
            </div><!-- .navigation -->
        <?php endif; ?>
    </div>
 <?php endif; // end have_comments() ?>
     <div class="leave-form">
        <?php 
        global $post_id;
        $you_may_use = __( 'You may use these <abbr title="HyperText Markup Language">HTML</abbr> tags and attributes: %s', 'dir');
        $must_login ='<a href="%s">logged in</a>'.__( 'You must be  to post a comment.', 'dir');
        $logged_in_as ='<a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>'. __('Logged in as', 'dir');
        $required_fields_mark = ' ' . __('Required fields are marked %s', 'dir');
        $required_text = sprintf($required_fields_mark , '<span class="required">*</span>' );
        $defaults = array( 'fields' => apply_filters( 'comment_form_default_fields', 
            array(
                'notes' => '',                
                'author' => '<p class="comment-form-author">'.
                '<label><i class="icon-user"></i>' . __( '', 'dir').
                ''.( $req ? __( '', 'dir') : '' ) .'<input placeholder="'.__('Enter Your Name','dir').'" id="author"  name="author" class="nameinput" type="text" value=""' .
                esc_attr( $commenter['comment_author'] ) . ' tabindex="1">' .
                '</label></p><!-- #form-section-author .form-section -->',                
                'email'  => '<p class="comment-form-email">' .
                '<label><i class="icon-envelope4"></i>'. __( '', 'dir').
                ''.( $req ? __( '', 'dir') : '' ) .''.
                '<input id="email"  name="email" placeholder="'.__('Email Address','dir').'" class="emailinput" type="text"  value=""' . 
                esc_attr(  $commenter['comment_author_email'] ) . ' size="30" tabindex="2"></label>' .
                '</p><!-- #form-section-email .form-section -->',                
                'url'    => '<p class="comment-form-website">' .
                '<label><i class="icon-globe6"></i>' . __( '', 'dir') . '' .
                '<input id="url" name="url" type="text" placeholder="'.__('Website','dir').'" class="websiteinput"  value="" size="30" tabindex="3">' .
                '</label></p>' ) ),                
                'comment_field' => '<p class="comment-form-comment fullwidt">'.
                ''.__( '', 'dir'). ''.( $req ? __( '', 'dir') : '' ) .'' .
                '<label>
                    <i class="icon-comments-o"></i>
                    <textarea id="comment_mes" placeholder="'.__('Enter Message','dir').'" name="comment"  class="commenttextarea" rows="55" cols="15"></textarea>' .
                '</label>
                </p>',                
                'must_log_in' => '<p>' .  sprintf( $must_login,    wp_login_url( apply_filters( 'the_permalink', get_permalink( $post_id ) ) ) ) . '</p>',
                'logged_in_as' => '<p>' . sprintf( $logged_in_as, admin_url( 'profile.php' ), $user_identity, wp_logout_url( apply_filters( 'the_permalink', get_permalink( $post_id ) ) ) ).'</p>',
                'comment_notes_before' => '',
                'comment_notes_after' =>  '',
                'class_form' => 'comment-form contact-form',
                'id_form' => 'form-style',
                'class_submit' => 'submit-btn cs-bgcolor',
                'id_submit' => 'cs-bg-color',
                'title_reply' => __( 'Leave us a comment', 'dir' ),
                'title_reply_to' =>'<h4 class="cs-section-title">'.__( 'Leave us a comment', 'dir' ).'</h4>',
                'cancel_reply_link' => __( 'Cancel reply', 'dir' ),
                'label_submit' => __( 'Submit', 'dir' ),); 
                comment_form($defaults, $post_id); 
            ?>
    </div>
 
<!-- Col Start -->
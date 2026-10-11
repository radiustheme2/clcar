<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\ClCar;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$comments_number = get_comments_number();
$comments_text   = sprintf( '(%s)', number_format_i18n( $comments_number ) );
$length          = RDTheme::$options['excerpt_length'];
$has_entry_meta  = RDTheme::$options['blog_author_name'] || RDTheme::$options['blog_comment_num'] || RDTheme::$options['blog_date'] ? true : false;
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'blog-box' ); ?>>

	<?php if ( has_post_thumbnail() ): ?>
        <div class="post-img">
            <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('rdtheme-size2'); ?></a>
        </div>
	<?php endif; ?>

    <div class="post-content">

		<?php if ( $has_entry_meta ): ?>
            <div class="post-meta rt-theme-post-meta">
                <ul class="entry-meta">
					<?php if ( RDTheme::$options['blog_author_name'] ): ?>
                        <li>
                            <i class="icon-user"></i><span class="vcard author"><a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" class="fn"><?php the_author(); ?></a>
                            </span>
                        </li>
					<?php endif; ?>
					<?php if ( RDTheme::$options['blog_date'] ): ?>
                        <li><i class="icon-calendar-1"></i><span class="updated published"><?php the_time( get_option( 'date_format' ) ); ?></span></li>
					<?php endif; ?>
					<?php if ( RDTheme::$options['blog_cat_visibility'] && has_category() ): ?>
                        <li><i class="fa-solid fa-tag"></i><?php the_category( ', ' ); ?></li>
					<?php endif; ?>
					<?php if ( RDTheme::$options['blog_comment_num'] ): ?>
                        <li><i class="icon-comment" aria-hidden="true"></i><?php echo esc_html( $comments_text ); ?></li>
					<?php endif; ?>
					<?php if ( RDTheme::$options['blog_archive_reading_time'] ): ?>
                        <li>
                            <i class="fa-solid fa-clock"></i><span data-toggle="tooltip" data-placement="bottom" title="<?php echo esc_attr__( 'Reading Time', 'clcar' ) ?>" data-original-title="<?php echo esc_attr__( 'Reading Time', 'clcar' ) ?>">
                                <?php echo wp_kses_post( Helper::reading_time_count(get_the_content(), true ) ); ?>
                            </span>
                        </li>
					<?php endif; ?>
                </ul>
            </div>
		<?php endif; ?>

		<?php if ( ! empty( get_the_title() ) ): ?>
            <h3 class="post-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h3>
		<?php endif; ?>

        <div class="post-excerprt-wrap">
            <p><?php echo wp_kses_post( wp_trim_words( get_the_excerpt(), $length ) ); ?></p>
        </div>
        <div class="read-more-grid-btn">
            <a href="<?php the_permalink(); ?>" class="">
                <?php echo esc_html__( 'Read More', 'clcar' ); ?>
                <i class="icon-arrow-right-bold2"></i>
            </a>
        </div>
    </div>

</article>
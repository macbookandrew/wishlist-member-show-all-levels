<?php
/**
 * Plugin Name: WishList Member: Show All Levels
 * Plugin URI: https://github.com/macbookandrew/wishlist-member-show-all-levels
 * GitHub Plugin URI: https://github.com/macbookandrew/wishlist-member-show-all-levels
 * Description: Provides a shortcode that outputs all levels a member is allowed to access
 * Version: 1.1.1
 * Author: AndrewRMinion Design
 * Author URI: http://andrewrminion.com/
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/**
 * Show WishListMember content on the dashboard page with Woocommerce data
 */
function wlmsal_show_authorized_levels( $atts ) {
    // get attributes
    $attributes = shortcode_atts( array(
        'show_header'       => 'true',
        'pages_to_ignore'   => array()
    ), $atts );

    // get all levels this user is authorized for
    $authorized_levels = wlmapi_get_member_levels( get_current_user_id() );

    //initialize variable
    $shortcode_output = NULL;

    // loop over authorized levels, displaying all content for each
    if ( $authorized_levels ) {
        $shortcode_output .= '<div class="wishlist-member-levels wpex-row vcex-portfolio-grid wpex-clr entries vcex-isotope-grid">';
        foreach ( $authorized_levels as $level ) {
            // get all pages and posts for this level
            $this_level_pages = wlmapi_get_level_pages( $level->Level_ID );

            // loop over all pages for this level
            foreach ( $this_level_pages['pages']['page'] as $this_page ) {
                if ( ! in_array( $this_page['ID'], explode( ',', $attributes['pages_to_ignore'] ) ) ) {
                    $shortcode_output .= '<div class="portfolio-entry span_1_of_3 col vcex-isotope-entry portfolio type-portfolio entry has-media">
                        <div class="portfolio-entry-media entry-media overlay-parent overlay-parent-view-lightbox-buttons-buttons">
                            <a href="' . esc_url( get_permalink( $this_page['ID'] ) ) .'" title="' . esc_attr( get_the_title( $this_page['ID'] ) ) . '" class="portfolio-entry-media-link"><img src="' . esc_url( wp_get_attachment_image_src( get_post_thumbnail_id( $this_page['ID'] ) )[0] ) . '" width="600" height="600" class="portfolio-entry-img" alt="' . esc_attr( get_the_title( $this_page['ID'] ) ) . '" /></a>
                            <div class="overlay-view-lightbox-buttons overlay-hide theme-overlay">
                                <div class="overlay-view-lightbox-buttons-inner clr">
                                    <div class="overlay-view-lightbox-buttons-buttons clr">
                                        <a href="' . esc_url( get_permalink( $this_page['ID'] ) ) . '" class="view-post" title="' . esc_attr( get_the_title( $this_page['ID'] ) ) . '"><span class="fa fa-arrow-right"></span></a>
                                    </div><!-- .overlay-view-lightbox-buttons-buttons -->
                                </div><!-- .overlay-view-lightbox-buttons-inner -->
                            </div><!-- .overlay-view-lightbox-buttons -->
                        </div><!-- .portfolio-entry-media -->
                        <div class="portfolio-entry-details entry-details wpex-clr">
                            <h2 class="portfolio-entry-title entry-title"><a href="' . esc_url( get_permalink( $this_page['ID'] ) ) . '" title="' . esc_attr( get_the_title( $this_page['ID'] ) ) . '">' . esc_attr( get_the_title( $this_page['ID'] ) ) . '</a></h2>
                            <div class="portfolio-entry-excerpt wpex-clr">
                                <p>' . get_the_excerpt( $this_page['ID'] ) . '</p>
                            </div><!-- .portfolio-entry-excerpt -->
                            <div class="portfolio-entry-readmore-wrap wpex-clr">
                                <a href="' . esc_url( get_permalink( $this_page['ID'] ) ) . '" title="TRAIN" rel="bookmark" class="theme-button animate-on-hover flat white">TRAIN<span class="vcex-readmore-rarr">→</span></a>
                            </div><!-- .portfolio-entry-readmore-wrap -->
                        </div><!-- .portfolio-entry-details -->
                    </div><!-- .portfolio-entry -->';
                }
            }
        }
        $shortcode_output .= '</div><!-- .wishlist-member-levels -->';
    }
    // return all the content
    return $shortcode_output;
}
add_shortcode( 'wlm_all_authorized_levels', 'wlmsal_show_authorized_levels' );

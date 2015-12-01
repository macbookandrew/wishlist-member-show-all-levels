<?php
/**
 * Plugin Name: WishList Member: Show All Levels
 * Plugin URI: https://github.com/macbookandrew/wishlist-member-show-all-levels
 * GitHub Plugin URI: https://github.com/macbookandrew/wishlist-member-show-all-levels
 * Description: Provides a shortcode that outputs all levels a member is allowed to access
 * Version: 1.2
 * Author: AndrewRMinion Design
 * Author URI: https://andrewrminion.com/
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
        $shortcode_output .= '<div class="wishlist-member-levels">';
        foreach ( $authorized_levels as $level ) {

            // get all pages and posts for this level
            $this_level_pages = wlmapi_get_level_pages( $level->Level_ID );
            if ( $this_level_pages ) {
                $authorized_pages_array = array();
            }

            // loop over all pages for this level, adding them to an array for WP query
            foreach ( $this_level_pages['pages']['page'] as $this_page ) {
                if ( ! in_array( $this_page['ID'], explode( ',', $attributes['pages_to_ignore'] ) ) ) {
                    $authorized_pages_array[] = $this_page['ID'];
                }
            }

            // WP_Query arguments
            $args = array (
                'post__in'               => $authorized_pages_array,
                'post_type'              => array( 'page' ),
                'posts_per_page'         => '-1',
                'orderby'                => array( 'menu_order', 'title' ),
                'cache_results'          => true,
                'update_post_meta_cache' => true,
            );

            // The Query
            $authorized_pages_query = new WP_Query( $args );

            // The Loop
            if ( $authorized_pages_query->have_posts() ) {
                // show level header
                if ( 'true' == $attributes['show_header'] ) {
                    $shortcode_output .= '<h2>' . $level->Name . '</h2>';
                }

                // start list output
                $shortcode_output .= '<ul>';

                // loop through posts
                while ( $authorized_pages_query->have_posts() ) {
                    $authorized_pages_query->the_post();
                    $shortcode_output .= '<li><a href="' . get_permalink() . '">' . get_the_title() . '</a></li>';
                }

                $shortcode_output .= '</ul>';
            }

            // Restore original Post Data
            wp_reset_postdata();

        }
        $shortcode_output .= '</div>';
    }

    // return all the content
    return $shortcode_output;
}
add_shortcode( 'wlm_all_authorized_levels', 'wlmsal_show_authorized_levels' );

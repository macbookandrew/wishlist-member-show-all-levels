<?php
/**
 * Plugin Name: WishList Member: Show All Levels
 * Plugin URI: https://github.com/macbookandrew/wishlist-member-show-all-levels
 * GitHub Plugin URI: https://github.com/macbookandrew/wishlist-member-show-all-levels
 * Description: Provides a shortcode that outputs all levels a member is allowed to access
 * Version: 1.5.2
 * Author: Andrew Minion
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
        'pages_to_ignore'   => array(),
        'pages_to_include'  => array(),
        'group_by_level'    => 'false'
    ), $atts );

    // get all levels this user is authorized for
    $authorized_levels = wlmapi_get_member_levels( get_current_user_id() );

    //initialize variable
    $shortcode_output = NULL;

    // open container
    $shortcode_output .= apply_filters( 'wlm_all_levels_container_open', '<div class="wishlist-member-levels">' );

    // loop over authorized levels, displaying all content for each
    if ( $authorized_levels ) {

        // group by level
        if ( 'true' === $attributes['group_by_level'] ) {
            foreach ( $authorized_levels as $level ) {

                // get all pages and posts for this level
                $this_level_pages = wlmapi_get_level_pages( $level->Level_ID );
                if ( $this_level_pages ) {
                    $authorized_pages_array = array();
                }

                // loop over all pages for this level, adding them to an array for WP query
                foreach ( $this_level_pages['pages']['page'] as $this_page ) {
                    $authorized_pages_array[] = $this_page['ID'];
                }

                // add included pages
                if ( $attributes['pages_to_include'] ) {
                    $authorized_pages_array = array_merge( $authorized_pages_array, explode( ',', $attributes['pages_to_include'] ) );
                }

                // add filter
                $authorized_pages_array = apply_filters( 'wlm_authorized_pages_array', $authorized_pages_array );

                // WP_Query arguments
                $args = array (
                    'post__in'               => $authorized_pages_array,
                    'post_type'              => array( 'page' ),
                    'posts_per_page'         => '-1',
                    'orderby'                => array( 'menu_order', 'title' ),
                    'cache_results'          => true,
                    'update_post_meta_cache' => true,
                );

                // The Loop
                $shortcode_output .= get_all_authorized_pages( $args, $attributes, $level );

            }
        } else { // or not
            $authorized_pages_array = array();
            foreach ( $authorized_levels as $level ) {

                // get all pages and posts for this level
                $this_level_pages = wlmapi_get_level_pages( $level->Level_ID );

                // loop over all pages for this level, adding them to an array for WP query
                if ( $this_level_pages ) {
                    foreach ( $this_level_pages['pages']['page'] as $this_page ) {
                        $authorized_pages_array[] = $this_page['ID'];
                    }
                }
            }

            // add included pages
            if ( $attributes['pages_to_include'] ) {
                $authorized_pages_array = array_merge( $authorized_pages_array, explode( ',', $attributes['pages_to_include'] ) );
            }

            // remove hidden pages
            if ( $attributes['pages_to_ignore'] ) {
                $authorized_pages_array = array_diff( $authorized_pages_array, explode( ',', $attributes['pages_to_ignore'] ) );
            }

            // add filter
            $authorized_pages_array = apply_filters( 'wlm_authorized_pages_array', $authorized_pages_array );

            // WP_Query arguments
            $args = array (
                'post__in'               => $authorized_pages_array,
                'post_type'              => apply_filters( 'wlm_authorized_post_types', array( 'page' ) ),
                'posts_per_page'         => '-1',
                'orderby'                => array( 'menu_order', 'title' ),
                'cache_results'          => true,
                'update_post_meta_cache' => true,
            );

            // The Loop
            $shortcode_output .= get_all_authorized_pages( $args, $attributes );
        }
    } else {
        $shortcode_output .= apply_filters( 'wlm_no_authorized_levels_message', '<p>Sorry, you are not authorized to access any content. Please <a href="' . admin_url() . '">log in</a>, check your subscription status, or contact us for more information.</p>' );
    }
    // close container
    $shortcode_output .= apply_filters( 'wlm_all_levels_container_close', '</div>' );

    // return all the content
    return $shortcode_output;
}
add_shortcode( 'wlm_all_authorized_levels', 'wlmsal_show_authorized_levels' );

// get all authorized pages
function get_all_authorized_pages( $args, $attributes, $level ) {
    $authorized_content = NULL;

    // WP Query
    $authorized_pages_query = new WP_Query( $args );

    if ( $authorized_pages_query->have_posts() ) {
        // show level header
        if ( 'true' === $attributes['show_header'] ) {
            $authorized_content .= '<h2>' . $level->Name . '</h2>';
        }

        // start list output
        $authorized_content .= apply_filters( 'wlm_all_levels_level_wrapper_open', '<ul>' );

        // loop through posts
        while ( $authorized_pages_query->have_posts() ) {
            $authorized_pages_query->the_post();
            $authorized_content .= apply_filters( 'wlm_all_levels_item_wrapper_open', '<li' );
            $item_classes = apply_filters( 'wlm_all_levels_item_wrapper_class', '' );
            if ( in_array( get_the_ID(), explode( ',', $attributes['pages_to_ignore'] ) ) ) {
                $item_classes .= ' hidden';
            }
            if ( $item_classes ) {
                $authorized_content .= ' class="' . $item_classes . '"';
            }
            $authorized_content .= '>';
            $authorized_content .= apply_filters( 'wlm_all_levels_item_link', '<a href="' . get_permalink() . '">' . get_the_title() . '</a>', get_the_ID() );
            $authorized_content .= apply_filters( 'wlm_all_levels_item_wrapper_close', '</li>' );
        }

        $authorized_content .= apply_filters( 'wlm_all_levels_level_wrapper_close', '</ul>' );
    } else {
        $authorized_content .= apply_filters( 'wlm_no_authorized_content_message', '<p>Sorry, you are not authorized to access any content. Please <a href="' . admin_url() . '">log in</a>, check your subscription status, or contact us for more information.</p>' );
    }

    // restore original post data
    wp_reset_postdata();

    // return data
    return $authorized_content;
}

<?php
/**
 * Plugin Name: WishList Member: Show All Levels
 * Plugin URI: https://github.com/macbookandrew/wishlist-member-show-all-levels
 * GitHub Plugin URI: https://github.com/macbookandrew/wishlist-member-show-all-levels
 * Description: Provides a shortcode that outputs all levels a member is allowed to access
 * Version: 1.1
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
    $attributes = shortcode_atts(
        'show_header'       => 'true',
        'pages_to_ignore'   => array()
    );

    // get all levels this user is authorized for
    $authorized_levels = wlmapi_get_member_levels( get_current_user_id() );

    //initialize variable
    $shortcode_output = NULL;

    // loop over authorized levels, displaying all content for each
    foreach ( $authorized_levels as $level ) {

        $shortcode_output .= '<div class="wishlist-member-levels">';

        // show level header
        if ( $attributes['show_header'] ) {
            $shortcode_output .= '<h2>' . $level->Name . '</h2>';
        }

        // start list output
        $shortcode_output .= '<ul>';

        // get all pages and posts for this level
        $this_level_pages = wlmapi_get_level_pages( $level->Level_ID );

        // loop over all pages for this level
        foreach ( $this_level_pages['pages']['page'] as $this_page ) {
            if ( ! in_array( $this_page['ID'], $attributes['pages_to_ignore'] ) ) {
                $shortcode_output .= '<li><a href="' . esc_url( get_permalink( $this_page['ID'] ) ) . '">' . $this_page['name'] . '</a></li>';
            }
        }
        $shortcode_output .= '</ul>';
    }

    // return all the content
    return $shortcode_output;
}
add_shortcode( 'wlm_all_authorized_levels', 'wlmsal_show_authorized_levels' );

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
    $attributes = shortcode_atts( array(
        'show_header'       => 'true',
        'pages_to_ignore'   => array()
    ), $atts );

    // get all levels this user is authorized for
    $authorized_levels = wlmapi_get_member_levels( get_current_user_id() );

    //initialize variable
    $shortcode_output = NULL;

    // loop over authorized levels, displaying all content for each
    foreach ( $authorized_levels as $level ) {

        $shortcode_output .= '<div class="wishlist-member-levels wpex-row vcex-portfolio-grid wpex-clr entries vc_grid">';

        // get all pages and posts for this level
        $this_level_pages = wlmapi_get_level_pages( $level->Level_ID );

        // loop over all pages for this level
        foreach ( $this_level_pages['pages']['page'] as $this_page ) {
            if ( ! in_array( $this_page['ID'], explode( ',', $attributes['pages_to_ignore'] ) ) ) {
                $shortcode_output .= '<div class="vc_grid-item vc_clearfix vc_col-sm-4 vc_grid-item-zone-c-bottom vc_visible-item">
                    <div class="vc_gitem-zone vc_gitem-zone-a vc-gitem-zone-height-mode-auto vc-gitem-zone-height-mode-auto-1-1 vc_gitem-is-link" style="background-image: url(' . wp_get_attachment_image_src( get_post_thumbnail_id( $this_page['ID'] ) )[0] . ');">
                        <a href="' . esc_url( get_permalink( $this_page['ID'] ) ) . '" title="' . get_the_title( $this_page['ID'] ) . '">' . get_the_post_thumbnail( $this_page['ID'], 'post-thumbnail', array( 'class' => 'vc_gitem-zone-img' ) ) . '</a>
                        <div class="vc_gitem-zone-mini"></div>
                    </div>
                    <div class="vc_gitem-zone vc_gitem-zone-c">
                        <div class="vc_gitem-zone-mini">
                            <div class="vc_gitem_row vc_row vc_gitem-row-position-top">
                                <div class="vc_col-sm-12 vc_gitem-col vc_gitem-col-align-left">
                                    <div class="vc_custom_heading vc_gitem-post-data vc_gitem-post-data-source-post_title">
                                        <h4 style="text-align: left">' . get_the_title( $this_page['ID'] ) . '</h4>
                                    </div>
                                    <div class="vc_custom_heading vc_gitem-post-data vc_gitem-post-data-source-post_excerpt">
                                        <p style="text-align: left">' . get_the_excerpt( $this_page['ID'] ) . '</p>
                                    </div>
                                    <div class="vc_btn3-container vc_btn3-left"><a href="' . esc_url( get_the_permalink( $this_page['ID'] ) ) . '" class="vc_gitem-link vc_general vc_btn3 vc_btn3-size-md vc_btn3-shape-rounded vc_btn3-style-flat vc_btn3-color-white" title="Read more">Read more</a></div>
                                </div>
                            </div>
                        </div>
                    </div>';
            }
        }
        $shortcode_output .= '</div>';
    }

    // return all the content
    return $shortcode_output;
}
add_shortcode( 'wlm_all_authorized_levels', 'wlmsal_show_authorized_levels' );

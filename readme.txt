=== WishList Member: Show All Levels ===
Contributors: macbookandrew
Tags: wishlist,wishlist member,membership,authorized,level,levels,account
Donate link: https://cash.me/$AndrewRMinionDesign
Requires at least: 4.0
Tested up to: 4.6
Stable tag: 1.5.2
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Provides a shortcode that outputs all levels a member is allowed to access.

== Description ==

Have you ever wanted a way to show all the content a member is allowed to access? This plugin gives you a simple shortcode that lists all the pages a user is allowed to access, grouped by level.

== Installation ==
1. Install the plugin
2. Use the shortcode `[wlm_all_authorized_levels]` to show all authorized levels for the logged-in user.
3. See the [FAQ](faq/) for more options.

== Frequently Asked Questions ==
= Can I group pages by their level? =

Yes; use `[wlm_all_authorized_levels group_by_level="true"]` to group by pages by each level and (by default) show each level’s header.

= Can I hide the header? =

Yes; use `[wlm_all_authorized_levels show_header="false"]` to hide the header of each level (only works with `group_by_level`).

= Can I ignore specific pages? =

Yes; get the IDs of those pages from the WordPress admin page and then add them to the shortcode separated by commas, like this: `[wlm_all_authorized_levels pages_to_ignore="151,20"]`.

You can also use the `wlm_authorized_pages_array` filter to modify the array; see below for an example.

= Can I include specific pages? =

Yes, you can include specific pages using the `pages_to_include` attribute like this: `[wlm_all_authorized_levels pages_to_include="151,20"]`.

You can also use the `wlm_authorized_pages_array` filter to modify the array:

`
add_filter( 'wlm_authorized_pages_array', 'tweak_wlm_pages' );
function tweak_wlm_pages( $array ) {
    // add a page
    $array[] = $page_ID_to_add;

    // remove a page
    if ( $false !== ( $key = array_search( $page_ID_to_remove, $array ) ) ) {
        unset( $array[$key] );
    }

    // return the modified array
    return $array;
}
`

= Can I use a custom template? =

Not yet, but there are filters for every part of the output; here’s a list of the available filters:

- `wlm_authorized_post_types`: array of post types included in the WP_Query; defaults to `array( 'page' )`
- `wlm_all_levels_container_open`: wraps everything; defaults to `<div class="wishlist-member-levels">`
- `wlm_all_levels_container_close`: defaults to `</div>`
- `wlm_all_levels_level_wrapper_open`: wraps the entire list of items; defaults to `<ul>`
- `wlm_all_levels_level_wrapper_close`: defaults to `</ul>`
- `wlm_all_levels_item_wrapper_open`: wraps each item; defaults to `<li` (no closing bracket)
- `wlm_all_levels_item_wrapper_close`: defaults to `</li>`
- `wlm_all_levels_item_wrapper_class`: defaults to empty; space-separated list of classes to add to each item
- `wlm_all_levels_item_link`: defalts to `<a href="' . get_permalink() . '">' . get_the_title() . '</a>`; the post ID is available as a parameter to your callback function
- `wlm_no_authorized_levels_message`: defaults to `<p>Sorry, you are not authorized to access any content. Please <a href="' . admin_url() . '">log in</a>, check your subscription status, or contact us for more information.</p>`; shown when a user is not authorized for any WishList levels
- `wlm_no_authorized_content_message`: defaults to `<p>Sorry, you are not authorized to access any content. Please <a href="' . admin_url() . '">log in</a>, check your subscription status, or contact us for more information.</p>`; shown when there are no pages available

== Changelog ==

= 1.5.2 =
- Add filter for post types

= 1.5.1 =
- Add login url to customer message

= 1.5 =
- Add messages when no authorized levels or content is available to an end user

= 1.4.2 =
- Update documentation

= 1.4.1 =
- Add filter for modifying the array of pages before WP query

= 1.4 =
- Major change: defaults to showing pages in one list rather than grouped by level
- Old behavior still available with the `group_by_level="true"` attribute

= 1.3 =
- Add filters for customizing the output

= 1.2.1 =
- Add fix for edge case where levels with no protected pages would sometimes cause a white screen of death

= 1.2 =
- Use WP_Query to allow for better sorting

= 1.1.1 =
- Fix missing wrapper closing

= 1.1 =
- Add support for ignoring specific pages and removing the header

= 1.0 =
- Initial plugin

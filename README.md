# WishList Member #
**Contributors:** macbookandrew
**Tags:** wishlist,membership
**Donate link:** https://cash.me/$AndrewRMinionDesign
**Requires at least:** 4.0
**Tested up to:** 4.3.1
**License:** GPL2
**License URI:** https://www.gnu.org/licenses/gpl-2.0.html

Provides a shortcode that outputs all levels a member is allowed to access

## Installation ##
1. Install the plugin
2. Use the shortcode `[wlm_all_authorized_levels]` to show all authorized levels for the logged-in user.

## Frequently Asked Questions ##
### Can I hide the header? ###

Yes; use `[wlm_all_authorized_levels show_header=\"false\"]` to hide the header of each level.

### Can I ignore specific pages? ###

Yes; get the IDs of those pages from the WordPress admin page and then add them to the shortcode separated by commas, like this: `[wlm_all_authorized_levels pages_to_ignore=\"151,20\"]`.

## Changelog ##
1.1
- Add support for ignoring specific pages and removing the header

1.0
- Initial plugin

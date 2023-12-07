=== Sellers.json Editor ===
Contributors: TheWebist
Tags: comments, spam
Requires at least: 4.5
Tested up to: 6.4.1
Requires PHP: 7.0
Stable tag: 1.4.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Provides a WordPress interface for editing your site's sellers.json.

== Description ==

For a detailed description of the sellers.json, please see [the technical specification](https://iabtechlab.com/wp-content/uploads/2019/07/Sellers.json_Final.pdf) provided by [iab. Tech Lab](https://iabtechlab.com/sellers-json/).

== Changelog ==

= 1.4.1 =
* Plugin Update API bug fixes.

= 1.4.0 =
* Rewrite of plugin update API.

= 1.3.1 =
* Updating plugin check endpoint.
* Updating plugin check transient to two hours (7200 seconds).

= 1.3.0 =
* Implementing WordPress plugin update API.

= 1.2.0 =
* Rewriting endpoint for `/sellers.json`, using `init` hook.

= 1.1.0 =
* Saving ACF settings locally.

= 1.0.1 =
* Setting `publicly_queryable = false` for Sellers CPT.

= 1.0.0 =
* Initial release.
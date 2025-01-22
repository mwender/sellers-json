# Sellers.json Editor #
**Contributors:** [thewebist](https://profiles.wordpress.org/thewebist/)  
**Tags:** comments, spam  
**Requires at least:** 4.5  
**Tested up to:** 6.7.1  
**Requires PHP:** 7.0  
**Stable tag:** 1.5.2  
**License:** GPLv2 or later  
**License URI:** https://www.gnu.org/licenses/gpl-2.0.html  

The Sellers.json Editor is a WordPress plugin for adding and editing a sellers.json file to your website.

## Description ##

The Sellers.json Editor is a WordPress plugin which implments [the Sellers.Json specification](https://iabtechlab.com/sellers-json/) by IAB Tech Lab. It provides a WordPress backend mechanism for adding "Sellers" which are then displayed as JSON data in a sellers.json file available on your website.

For a detailed description of the sellers.json, please see [the technical specification](https://iabtechlab.com/wp-content/uploads/2019/07/Sellers.json_Final.pdf) provided by [iab. Tech Lab](https://iabtechlab.com/sellers-json/).

This plugin is maintained and distributed by [Wenmark Digital Solutions](https://wenmarkdigital.com).

## Changelog ##

### 1.5.2 ###
* Updating "Update API" to dynamically retrieve Description and Changelog sections from `README.md`.

### 1.5.1 ###
* Compiling README.

### 1.5.0 ###
* Updating plugin update check end point.
* Updating "Plugin URI".

### 1.4.2 ###
* Setting cache duration for plugin update check to 2 hours.

### 1.4.1 ###
* Plugin Update API bug fixes.

### 1.4.0 ###
* Rewrite of plugin update API.

### 1.3.1 ###
* Updating plugin check endpoint.
* Updating plugin check transient to two hours (7200 seconds).

### 1.3.0 ###
* Implementing WordPress plugin update API.

### 1.2.0 ###
* Rewriting endpoint for `/sellers.json`, using `init` hook.

### 1.1.0 ###
* Saving ACF settings locally.

### 1.0.1 ###
* Setting `publicly_queryable = false` for Sellers CPT.

### 1.0.0 ###
* Initial release.
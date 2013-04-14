=== Jump Around ===
Contributors: Matthew Trevino
Tags: a,s,d,z,x,cusotmize,navigating,navigation by key,keyboard,navi,quick,easy,useful
Requires at least: 3.3
Tested up to: 3.5.1
Stable tag: 2.2

Navigate posts by pressing the a,s,d,z, or x.  (Keys are customizable in options).

== Description ==

Jump Around allows your visitors to quickly navigate between posts by using the a,s,d,z, and x keys (to effectively "jump" 
to the post in line in the loop).  Or, customize the keys to do what you want them to.

== Installation ==

1. Upload /jump-around/ to your /wp-content/plugins directory.
2. Navigate to your plugins menu in your Wordpress admin.
3. Activate it.
4. You'll find settings for the plugin under Settings->Jump Around.

(Check changelog, entry 1.4.1 for an example of how to set the post containers properly)

== Changelog ==
= 2 =
* 2.2 - Prevent scrolling if input field is focused.
* 2.1 - style.css fixed
* Cleaned up code
* Options CSS should only load on the Jump Around options page.

= 1.4.1 =
* Greater control over CSS selectors
* If your post container is .post and your link is wrapped in an h2 with a class of entry-title, and your previous and next post links are called "previouspostslink" and "nextpostslink" (respectively), your settings for div selectors will look like this:
* Post container class: .post - Post permalink class: .entry-title a - Previous posts link wrapper - .previouspostslink - Next posts link wrapper: .nextpostslink

= 1.3 =
* Admin area styled.

= 1.2 =
* Added the ability to customize the keys being used (a-z,1-0,arrow keys)

= 1.1 =
* Added z and x (z = older posts, x = newer posts)

= 1.0 =
* Initial release.

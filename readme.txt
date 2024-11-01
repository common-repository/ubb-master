=== UBB Master ===
Contributors: bigasp
Tags: ubb, code, post, excerpt, comment
Requires at least: 2.7
Tested up to: 3.1
Stable tag: trunk

Simply replace your ubb code with html code. Very simple but sometimes you may need it.

== Description ==

UBB Master provides a way to add your own ubb code in your posts, excerpts and your comments.

<strong>What is ubb code:</strong>

A typical UBB code looks like this:
[search]put your keyword here...[/search]

<strong>And here are the rules to play:</strong>

You can use following marks to replace this ubb code with your own format:

!{content}: the content between [ubb] and [/ubb].

!{encoded_content}: the content encoded by urlencode, you may need it in forming a url.

!{attr:attribute_name}: the attribute called attribute_name.

!{encoded_attr:attribute_name}: the attribute encoded by urlencode.

<strong>For example:</strong>

&lt;a target=&quot;blank&quot; href=&quot;http://www.google.com/search?ie=UTF-8&amp;q=!{encoded_content}&quot;&gt;!{content}&lt;/a&gt;

Then the example ubb code above will be replaced by this:

&lt;a target=&quot;blank&quot; href=&quot;http://www.google.com/search?ie=UTF-8&amp;q=put+your+keyword+here%26%238230%3B&quot;&gt;put your keyword here&amp;#8230;&lt;/a&gt;

Quite simple, right?

If you found any problems, please contact me via <a href="mailto:iambigasp@gmail.com" target="_blank">iambigasp@gmail.com</a>. I will be very appreciate.

== Installation ==

The plugin is simple to install:

1. Download `ubb-master.zip`
2. Unzip
3. Upload `ubb-master` directory to your `/wp-content/plugins` directory
4. Go to the plugin management page and enable the plugin
5. Configure the plugin from `Manage/UBB Master`

== Frequently Asked Questions ==

= Why use this plugin =

Sometimes, you may need a simple ubb replacement to support your blog, such as search, music player and etc.

But you can't find a plugin which meets your need, and you decide to do it on your own. Then it may help you.

For example:

You can use [music]xxx.mp3[/music] instead of writting a bunch of code to insert a flash music player.

And once you want to change your player, just change your ubb format, and everything is done.

= Why not using enable ubb code in comments =

It may not be safe to use the ubb code in comments.

The source is open, thus the content posted may be well constructed to hack your blog.

However, you may need it in some situation. So I still leave it there.

Use it wisely.

== Screenshots ==

1. UBB List
2. Modify the existing ubb code
3. Add a new ubb code

== Changelog ==

= 0.1.0.0 =
* Replace ubb code in posts, excerpts and comments
* Support 4 basic marks to presents the content or the attributes.

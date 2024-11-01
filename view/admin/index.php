<?php

if( !defined('PLUGIN_MAGIC') ) {
    die('Access denied!');
}

print <<<EOT
<div class="wrap">
    <div id="icon-tools" class="icon32"><br></div>
    <h2>UBB Master | List <button class="button-primary" type="button" onclick="um_add_ubb();">Add a ubb code</button></h2>
    <div id="um-guide" class="tool-box hidden">
    <h3>Guide:</h3>
    <strong>What is ubb code:</strong><br />
    A ubb code looks like this: <br />
    [post_link id="123" anchor="comment"]UBB Master[/post_link]<br />
    <br />
    <strong>And here are the rules to play:</strong><br />
    !{content}: the content between [ubb] and [/ubb].<br />
    !{encoded_content}: the content encoded by urlencode, you may need it in forming a url.<br />
    !{attr:attribute_name}: the attribute called attribute_name.<br />
    !{encoded_attr:attribute_name}: the attribute encoded by urlencode.<br />
    Of course, more is comming up...<br />
    <br />
    And you can enable these ubb code in your post, excerpt and your comment.<br />
    <br />
    <strong>Example:</strong><br />
    Now we define a format to format the ubb code above like this:<br />
    &lt;a href=&quot;http://bigasp.com/?p=!{attr:id}#!{encoded_attr:anchor}&quot; target=&quot;blank&quot;&gt;!{content}&lt;/a&gt;<br />
    <br />
    And you will find all your "post_link" ubb code are changed into:<br />
    &lt;a href=&quot;http://bigasp.com/?p=123#comment&quot; target=&quot;blank&quot;&gt;UBB Master&lt;/a&gt;<br />
    <br />
    Ha! Simple but quite useful right?<br />
    <br />
    <a href="#" onclick="um_hide_guide();">&lt;&lt;&lt; Hide Guide &gt;&gt;&gt;&gt;</a>
    </div>
    <div id="um-note" class="tool-box">
    <h3>Note:</h3>
    If you don't know how to use, <a href="#" onclick="um_show_guide();">here is the guide</a>.<br />
    <br />
    Here are the keyword ubb master supports now. <br />
    !{content}: the content between [ubb] and [/ubb].<br />
    !{encoded_content}: the content encoded by urlencode, you may need it in forming a url.<br />
    !{attr:attribute_name}: the attribute called attribute_name.<br />
    !{encoded_attr:attribute_name}: the attribute encoded by urlencode.<br />
    </div>
    <form id="ubb-edit-form" action="./tools.php?page=ubb-master.php" method="post">
        <table class="widefat">
            <thead>
                <tr>
                    <th>UBB Name</th>
                    <th>Format</th>
                    <th>Post</th>
                    <th>Excerpt</th>
                    <th>Comment</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>UBB Name</th>
                    <th>Format</th>
                    <th>Post</th>
                    <th>Excerpt</th>
                    <th>Comment</th>
                </tr>
            </tfoot>
            <tbody id="ubb-list">
EOT;

foreach($ubb_format_list as $format) {
    $ubb_name = htmlspecialchars($format->name_);
    $ubb_format = htmlspecialchars($format->format_);
    $enable_in_post = $format->enable_in_post() ? '<font color="green">Y</font>' : '<font color="red">N</font>';
    $enable_in_excerpt = $format->enable_in_excerpt() ? '<font color="green">Y</font>' : '<font color="red">N</font>';
    $enable_in_comment = $format->enable_in_comment() ? '<font color="green">Y</font>' : '<font color="red">N</font>';
    $ubb_info = base64_encode($format->name_).':'.base64_encode($format->format_).':'.base64_encode($format->enable_in_post()).':'.base64_encode($format->enable_in_excerpt()).':'.base64_encode($format->enable_in_comment());
print <<<EOT

                <tr id="ubb-{$format->id_}">
                    <td>
                        {$ubb_name}
                        <div class="row-actions">
                            <a href="#" onclick="um_inline_edit_ubb('{$format->id_}');">Edit</a> - <a href="./tools.php?page=ubb-master.php&op=del&ubb-id={$format->id_}">Delete</a>
                        </div>
                        <div id="ubb-info-{$format->id_}" class="hidden">{$ubb_info}</div>
                    </td>
                    <td>{$ubb_format}</td>
                    <td>{$enable_in_post}</td>
                    <td>{$enable_in_excerpt}</td>
                    <td>{$enable_in_comment}</td>
                </tr>

EOT;
}

print <<<EOT
            </tbody>
        </table>
    </form>
    <form method="get" action="">
        <table style="display: none">
            <tbody>
                <tr id="ubb-edit" class="hidden">
                    <td colspan="5">
                        	<input type="hidden" name="op" value="save" />
                        	<input type="hidden" name="ubb-id" value="" />
                        	<table style="width:100%;">
                                <tr>
                                    <td width="200">UBB Name: </td>
                                    <td><input type="text" name="ubb-name" value="" size="40"/></td>
                                </tr>
                                <tr>
                                    <td class="title">UBB Format: </td>
                                    <td class="input-text-wrap"><textarea name="ubb-format" rows="3" cols="100"></textarea></td>
                                </tr>
                                <tr>
                                    <td class="title">Enable in post: </td>
                                    <td class="input-text-wrap"><input type="checkbox" name="ubb-enable-in-post" value="1" /></td>
                                </tr>
                                <tr>
                                    <td class="title">Enable in excerpt: </td>
                                    <td class="input-text-wrap"><input type="checkbox" name="ubb-enable-in-excerpt" value="1" /></td>
                                </tr>
                                <tr>
                                    <td>Enable in comment: </td>
                                    <td><input type="checkbox" name="ubb-enable-in-comment" value="1" /> (Be careful with this option, it may be unsafe.)</td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                    	<input type="submit" class="button-primary" value="Save"/>
                                    	<input type="button" class="button" value="Cancel" onclick="um_cancel_inline_edit();"/>
                                    </td>
                                </tr>
                            </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </form>
</div>
EOT;

?>
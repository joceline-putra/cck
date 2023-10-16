<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

function compress(){
    ini_set("pcre.recursion_limit", "16777");
    $CI =& get_instance();
    $buffer = $CI->output->get_output();

    $buffer = _removing_comment1($buffer); // step 1
    $buffer = _removing_comment2($buffer); // step 2
    $buffer = _removing_comment3($buffer); // step 3

    // source https://github.com/bcit-ci/CodeIgniter/wiki/Compress-HTML-output
    $reBuild = '%# Collapse whitespace everywhere but in blacklisted elements.
        (?>             # Match all whitespans other than single space.
          [^\S ]\s*     # Either one [\t\r\n\f\v] and zero or more ws,
        | \s{2,}        # or two or more consecutive-any-whitespace.
        ) # Note: The remaining regex consumes no text at all...
        (?=             # Ensure we are not in a blacklist tag.
          [^<]*+        # Either zero or more non-"<" {normal*}
          (?:           # Begin {(special normal*)*} construct
            <           # or a < starting a non-blacklist tag.
            (?!/?(?:textarea|pre|ins|blockquote)\b)
            [^<]*+      # more non-"<" {normal*}
          )*+           # Finish "unrolling-the-loop"
          (?:           # Begin alternation group.
            <           # Either a blacklist start tag.
            (?>textarea|pre|ins|blockquote)\b
          | \z          # or end of file.
          )             # End alternation group.
        )  # If we made it here, we are not in a blacklist tag.
        %Six';
    $new_buffer = preg_replace($reBuild, " ", $buffer);

    if ($new_buffer === null) {
        $new_buffer = $buffer;
    }

    // callback
    $new_buffer = _removing_comment2($new_buffer, true); // step 2
    $new_buffer = _removing_comment3($new_buffer, true); // step 3

    // result
    $CI->output->set_output($new_buffer);
    $CI->output->_display();
}

// removing comment <!-- -->
function _removing_comment1($buffer){
    $buffer = preg_replace('/<!--[\s\S]*?-->/', '', $buffer);
    return $buffer;
}

// removing comment /**/
function _removing_comment2($buffer, $callback = false){
    // tag input accept="image/*,audio/*,video/*"
    $image = [
        'text_ori' => "image/*",
        'regex' => "/image\/\*/",
        'alias' => "accept_all_image_from_tag_input_html",
    ];
    $audio = [
        'text_ori' => "audio/*",
        'regex' => "/audio\/\*/",
        'alias' => "accept_all_audio_from_tag_input_html"
    ];
    $video = [
        'text_ori' => "video/*",
        'regex' => "/video\/\*/",
        'alias' => "accept_all_video_from_tag_input_html"
    ];

    if($callback === true){
        // proses regex mengembalikan ke text originalnya
        $buffer = preg_replace("/".$image['alias']."/", $image['text_ori'], $buffer);
        $buffer = preg_replace("/".$audio['alias']."/", $audio['text_ori'], $buffer);
        $buffer = preg_replace("/".$video['alias']."/", $video['text_ori'], $buffer);

        return $buffer;
    }

    // proses regex mengubah text kebentuk aliasnya
    $buffer = preg_replace($image['regex'], $image['alias'], $buffer);
    $buffer = preg_replace($audio['regex'], $audio['alias'], $buffer);
    $buffer = preg_replace($video['regex'], $video['alias'], $buffer);

    // removing comment /**/
    $buffer = preg_replace('/(?:\/\*(?:[\s\S]*?)\*\/)/', '', $buffer);
    return $buffer;
}

// removing comment //
function _removing_comment3($buffer, $callback = false){
    // text `://` from link example "https://example.com"
    $link = [
        'text_ori' => '://',
        'regex' => '/\:\/\//',
        'alias' => "link_alias_double_point_and_double_slash"
    ];
    // text javascript `//<![CDATA[` and `//]]>`
    $cdata_start = [
        'text_ori' => '//<![CDATA[',
        'regex' => '/\/\/\<\!\[CDATA\[/',
        'alias' => 'tag_javascript_cdata_start'
    ];
    $cdata_end = [
        'text_ori' => '//]]>',
        'regex' => '/\/\/\]\]\>/',
        'alias' => 'tag_javascript_cdata_end'
    ];

    if($callback === true){
        // proses regex mengembalikan ke text originalnya
        $buffer = preg_replace("/".$link['alias']."/", $link['text_ori'], $buffer);
        $buffer = preg_replace("/".$cdata_start['alias']."/", $cdata_start['text_ori'], $buffer);
        $buffer = preg_replace("/".$cdata_end['alias']."/", $cdata_end['text_ori'], $buffer);

        return $buffer;
    }

    // proses regex mengubah text kebentuk aliasnya
    $buffer = preg_replace($link['regex'], $link['alias'], $buffer);
    $buffer = preg_replace($cdata_start['regex'], $cdata_start['alias'], $buffer);
    $buffer = preg_replace($cdata_end['regex'], $cdata_end['alias'], $buffer);

    // removing comment //
    $buffer = preg_replace('/\/\/.*/', '', $buffer);
    return $buffer;
}

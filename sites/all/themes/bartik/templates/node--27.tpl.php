<?php

list_code_samples();

function list_code_samples()
{
    $content_type = "code_sample";
    $nodes = node_load_multiple(array(), array('type' => $content_type));

    //printvar($nodes);
    $code_sample_list = array();

    foreach ($nodes as $node) {
        $tid = $node->field_product_family['und'][0]['tid'];
        $family = tid_to_name($tid);
        $title = $node->title;
        //$code_sample_list[$family][] = $title;

        $formtype = field_get_items('node', $node, 'field_code_sample');
        $features = array();
        $x = 0;
        foreach ($formtype as $itemid) {
            $item = field_collection_field_get_entity($itemid);

            $programming_language = $item->field_programming_language['und'][0]['tid'];
            $programming_language = tid_to_name($programming_language);

            $file_attachment = $item->field_file_attachments['und'][0]['filename'];
            //$programming_language = tid_to_name($programming_language);

            $code_sample_notes = $item->field_documentation_notes['und'][0]['value'];

            $code_sample_list[$family][$title][$x]['language'] = $programming_language;
            $code_sample_list[$family][$title][$x]['file'] = $file_attachment;
            $code_sample_list[$family][$title][$x]['notes'] = $code_sample_notes;

            $x++;

//            /printvar($item);
        }
    }

    // Output Display
    $output = "<div class='code-container'>";
    foreach ($code_sample_list as $item) {

    }
    // print headers & data
    foreach($code_sample_list as $key => $val) {
        $title = $key;
        $output .= "<h3 class='section-title'>" . $title . "</h3>";

        foreach($val as $key2 => $row) {
            $output .= "<div class='sample-container'>";
            $output .= "<div class='product-name'>$key2</div>";
            $output .= "<div class='code-sample-group'>";
            foreach ($row as $ke32 => $val3) {
                $language = $val3['language'];
                $notes = $val3['notes'];
                $file = $val3['file'];
                $download_link = "<a href='http://google.com/'>Download</a>";

                $output .= "<div class='data'>";
                $output .= "$language | $download_link<br>";
                if(!empty($notes)) {
                    $output .= "<span class='notes'>Notes: $notes</span>";
                }
                $output .= "</div>";
                //printvar($val3);
            }
            $output .= "</div>";
        }
        $output .= "</div>";
    }

    $output .= "</div>";

    echo $output;

    //printvar($code_sample_list);
}




function printvar($array) {
    echo "<pre>";
    print_r($array);
    echo "</pre>";
}
function tid_to_name($tid) {
    $name = taxonomy_term_load($tid);
    $name = $name->name;
    return $name;
}
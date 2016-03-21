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
        $nid = $node->nid;
        //$code_sample_list[$family][] = $title;

        $formtype = field_get_items('node', $node, 'field_code_sample');
        $features = array();
        $x = 0;
        foreach ($formtype as $itemid) {
            $item = field_collection_field_get_entity($itemid);

            //printvar($item);

            $programming_language = $item->field_programming_language['und'][0]['tid'];
            $programming_language = tid_to_name($programming_language);

            $file_attachment = $item->field_file_attachments['und'][0]['uri'];
            //$programming_language = tid_to_name($programming_language);

            $code_sample_notes = $item->field_documentation_notes['und'][0]['value'];
            $code_sample_date = $item->field_file_attachments['und'][0]['timestamp'];

            $code_sample_list[$family][$title][$x]['language'] = $programming_language;
            $code_sample_list[$family][$title][$x]['file'] = $file_attachment;
            $code_sample_list[$family][$title][$x]['notes'] = $code_sample_notes;
            $code_sample_list[$family][$title][$x]['date'] = $code_sample_date;
            $code_sample_list[$family][$title][$x]['nid'] = $nid;

            $x++;

            //printvar($item);
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

        /*foreach($val as $key2 => $row) {
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
        }*/

        $output .= "<table class='code-samples'><tbody>";
        $i = 1;
        global $base_url;
        global $user;
        foreach($val as $key2 => $row) {
            if($i % 2 == 0) {
                $evenoddclass = 'even';
            }
            else {
                $evenoddclass = 'odd';
            }
            $i++;
            $output .= "<tr class='$evenoddclass'>";
            $output .= "<td class='product-name'>$key2</td>";
            $output .= "<td class='code-sample-group'>";
            $rowcount = count($row);
            $x = 1;
            foreach ($row as $ke32 => $val3) {
                $language = $val3['language'];
                $notes = $val3['notes'];
                $nid = $val3['nid'];
                $file = $val3['file'];
                $doc_url = uriToUrl($file);
                $file = "<a href='/$doc_url' target='_blank' class='attachment-link'>$doc_url</a>";
                $date = $val3['date'];
                $date = date('m.d.Y', $date);
                $download_link = "<a href='$base_url/$doc_url'>Download</a>";

                $output .= "<span class='prog-language'>$language</span><span class='download-link'>$download_link</span><span class='date'>$date</span>";

                if($user->uid) {
                    $editlink = $base_url . "/node/" . $nid . "/edit";
                    $output .= "<a href=$editlink>edit</a>";
                }
                $x++;
                if($x <= $rowcount) {
                    $output .= "<div class='breakspace'></div>";
                }
            }
            $output .= "</td></tr>";
        }
        $output .= "</tbody></table>";
    }

    $output .= "</div>";
    echo $output;
}


/** Helper Functions */
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
function uriToUrl($uri) {
    global $base_url;
    $url = file_create_url($uri);
    $url = str_replace($base_url .'/', '', $url);
    return $url;
}
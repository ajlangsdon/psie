<?php

list_code_samples();

function list_code_samples()
{
    $content_type = "code_sample";
    $nodes = node_load_multiple(array(), array('type' => $content_type));

    //printvar($nodes);
    $code_sample_list = array();
    $code_samples = array();
    $csa = array();

    foreach ($nodes as $node) {
        $tid = $node->field_product_family['und'][0]['tid'];
        $family = tid_to_name($tid);
        $title = $node->title;
        $nid = $node->nid;
        //$code_sample_list[$family][] = $title;

        $formtype = field_get_items('node', $node, 'field_code_sample');
        $features = array();
        $x = 0;
        $cat = array();
        foreach ($formtype as $itemid) {
            $item = field_collection_field_get_entity($itemid);

            //printvar($item);

            $programming_language = $item->field_programming_language['und'][$x]['tid'];
            $programming_language = tid_to_name($programming_language);

            $y = 0;
//            /printvar($item->field_file_attachments);
            foreach($item->field_file_attachments['und'] as $fa) {
                //printvar($item);
                //printvar($fa);
                $csa[$family][$title][$programming_language]['file'][$y]['uri'] = $fa['uri'];
                $csa[$family][$title][$programming_language]['file'][$y]['timestamp'] = $fa['timestamp'];
                $csa[$family][$title][$programming_language]['file'][$y]['filename'] = $fa['filename'];
                $y++;
            }


            $file_attachment = $item->field_file_attachments['und'][$x]['uri'];
            //$programming_language = tid_to_name($programming_language);

            $code_sample_notes = $item->field_documentation_notes['und'][$x]['value'];
            $code_sample_date = $item->field_file_attachments['und'][$x]['timestamp'];

            //$code_sample_list[$family][$title][$programming_language][] = $programming_language;
            $code_sample_list[$family][$title][$programming_language]['file'] = $file_attachment;
            $code_sample_list[$family][$title][$programming_language]['notes'] = $code_sample_notes;
            $code_sample_list[$family][$title][$programming_language]['date'] = $code_sample_date;
            $code_sample_list[$family][$title][$programming_language]['nid'] = $nid;

            //$x++;
        }
    }

    //printvar($csa);

    // Output Display
    $output = "<div class='code-container'>";
    foreach ($code_sample_list as $item) {

    }
    // print headers & data
    foreach($csa as $key => $val) {
        $title = $key;
        $output .= "<h3 class='section-title'>" . $title . "</h3>";

        $output .= "<table class='code-samples'>";
        /*$output .= "<thead>";
        $output .= "    <tr><th>Product</th><th>Language</th><th>Download File</th><th>Date Added</th>";
        $output .= "</thead>";*/
        $output .= "<tbody>";
        $xyz = 1;
        global $base_url;
        global $user;
        //printvar($val);
        foreach($val as $key2 => $row) {
            if($xyz % 2 == 0) {
                $evenoddclass = 'even';
            }
            else {
                $evenoddclass = 'odd';
            }
            $xyz++;
            $output .= "<tr class='$evenoddclass'>";
            $output .= "<td class='product-names'>$key2</td>";

            $x = 1;
            $z = 1;
            //printvar($row);
            $output .= "<td><table>";
            $z = 1;
            foreach ($row as $language => $row3) {
                $rowcount2 = count($row);
                if($z % 2 == 0) {
                    $evenoddclass = 'even';
                }
                else {
                    $evenoddclass = 'odd';
                }
                $output .= "<tr class=''><td class='code-sample-language'><span class='prog-language'>$language</span></td>";
                $z++;
                $output .= "<td class='code-sample-data'>";
                $output .= "<table>";
                //printvar($row3);
                foreach($row3 as $row4) {
                    //printvar($row4);
                    $t = 1;
                    foreach($row4 as $row5) {
                        if($t % 2 == 0) {
                            $evenoddclass = 'even';
                        }
                        else {
                            $evenoddclass = 'odd';
                        }
                        if($t == 1) {
                            $firstclass = 'first';
                        }
                        else { $firstclass = ''; }
                        $output .= "<tr class='$firstclass'>";
                        $uri = $row5['uri'];
                        $timestamp = $row5['timestamp'];
                        $date = date('m.d.Y', $timestamp);
                        $filename = $row5['filename'];
                        $fileurl = uriToUrl($uri);
                        $download_link = "<a href='$base_url/$fileurl'>$filename</a>";
                        $output .= "<td class='download-link'>$download_link</td><td class='date'>$date</td>";
                        $output .= "</tr>";
                        $t++;
                    }
                }
                $output .= "</tr></table>";
                $output .= "</td>";

            }
            $output .= "</td></table>";
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
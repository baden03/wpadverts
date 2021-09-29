<?php

function adverts_field_checkbox_block( $field, $form = null, $type = "checkbox" ) {
    
    $opts = "";
    $i = 1;
    
    if( !isset( $field["rows"] ) ) {
        $field["rows"] = 1;
    }
    
    if( !isset( $field["value"] ) ) {
        $value = array();
    } elseif( !is_array( $field["value"] ) ) {
        $value = (array)$field["value"];
    } else {
        $value = $field["value"];
    }
    
    if(isset($field["options_callback"]) && !empty($field["options_callback"])) {
        $opt = call_user_func( $field["options_callback"], $field );
    } elseif(isset($field["options"])) {
        $opt = $field["options"];
    } else {
        trigger_error("You need to specify options source for field [{$field['name']}].", E_USER_ERROR);
        $opt = array();
    }

    foreach($opt as $v) {
        
        if( isset( $v["id"] ) ) {
            $id = $v["id"];
        } else {
            $id = $field["name"];
        }
        
        $id = apply_filters( "adverts_form_field_option_id", $id.'_'.$i, $v, $field, $i );

        $checkbox = new Adverts_Html("input", array(
            "type" => $type,
            "name" => $field["name"].'[]',
            "id" => $id,
            "value" => $v["value"],
            "class" => " atw-flex-none atw-self-center",
            "checked" => in_array($v["value"], $value) ? "checked" : null
        ));

        $label = new Adverts_Html("label", array(
            "for" => $id,
            "class" => "atw-flex-1 atw-inline-block atw-py-0 atw-px-2 atw-text-sm atw-text-gray-700 atw-align-baseline atw-truncate atw-cursor-pointer"
        ),  $v["text"]);
        
        if( isset( $field["class"] ) ) {
            $class = $field["class"];
        } else {
            $class = null;
        }
        
        if( isset( $v["depth"] ) ) {
            $depth = $v["depth"];
        } else {
            $depth = 0;
        }

        if( $field["rows"] == 1 ) {
            $padding = str_repeat("&nbsp; &nbsp;", $depth * 2);
        } else {
            $padding = "";
        }
        
        $wrap = new Adverts_Html("div", array(
            "class" => "atw-flex atw-flex-row atw-flex-grow atw-align-baseline",
        ), $padding . $checkbox->render() . $label->render() );
        
        $opts .= $wrap->render();
        
        $i++;
    }
    
    //$field["rows"] = 3;

    $wrap_classes = array();
    if( absint( $field["rows"] ) >= 1 ) {
        $wrap_classes[] = sprintf( "atw-grid atw-grid-cols-%d atw-gap-3", absint( $field["rows"] ) );
    } else {
        $wrap_classes[] = "atw-flex atw-flex-wrap atw-flex-row atw-content-evenly";
    }
    
    echo Adverts_Html::build("div", array("class"=> join( " ", $wrap_classes ) ), $opts);
}

function adverts_field_radio_block( $field, $form = null ) {
    adverts_field_checkbox_block($field, $form, "radio");
}

function wpadverts_block_button( $args = array(), $options = array() ) {
//echo "<pre>";print_r($options);echo "</pre>";

    $args = array(
        "classes_prepend" => "atw-w-full",
        "classes_append" => ""
    );

    $defaults = array(
        "classes"           => "atw-text-base atw-outline-none atw-bg-none atw-border atw-border-solid atw-font-semibold atw-px-4",
        "text"              => "Search",
        "icon"              => "fa-search",
        "icon-position"     => "left"
    );

    $radius_options = array(
        0 => "atw-rounded-none",
        1 => "atw-rounded-sm",
        2 => "atw-rounded",
        3 => "atw-rounded-md",
        4 => "atw-rounded-lg",
        5 => "atw-rounded-xl",
        6 => "atw-rounded-full"
    );

    $width_options = array(
        0 => "atw-border-0",
        1 => "atw-border",
        2 => "atw-border-2",
        3 => "atw-border-4",
    );

    $leading_options = array(
        0 => "atw-leading-loose",
        1 => "atw-leading-loose",
        2 => "atw-leading-relaxed",
        3 => "atw-leading-relaxed"
    );

    if( isset( $options["border_radius"] ) && isset( $radius_options[ $options["border_radius"] ] ) ) {
        $border_radius = $radius_options[ $options["border_radius"] ];
    } else {
        $border_radius = $radius_options[0];
    }

    if( isset( $options["border_width"] ) && isset( $width_options[ $options["border_width"] ] ) ) {
        $border_width = $width_options[ $options["border_width"] ];
    } else {
        $border_width = $width_options[0];
    }

    $leading = "atw-leading-loose";

    $d_text = "";
    $d_icon = "";
    $m_text = "";
    $m_icon = "";

    switch( $options["desktop"] ) {
        case "text": 
            $d_text = "md:atw-inline"; 
            $d_icon = "md:atw-hidden"; 
            break;
        case "icon": 
            $d_text = "md:atw-hidden"; 
            $d_icon = "md:atw-inline";
            break;
        case "text-and-icon": 
            $d_text = "md:atw-inline";
            $d_icon = "md:atw-inline";
            break;
    }

    switch( $options["mobile"] ) {
        case "text": 
            $m_text = "atw-inline"; 
            $m_icon = "atw-hidden"; 
            break;
        case "icon": 
            $m_text = "atw-hidden"; 
            $m_icon = "atw-inline";
            break;
        case "text-and-icon": 
            $m_text = "atw-inline";
            $m_icon = "atw-inline";
            break;
    }

    ?>
    <button class="atw-inline-block hover:atw-bg-none atw-bg-none atw-text-white wpa-btn-primary atw-w-full atw-text-base atw-outline-none atw-border-solid hover:atw-bg-blue-700 atw-font-semibold atw-px-4 atw-py-2 <?php echo "$border_radius $border_width $leading" ?>">
        <span class=" <?php echo join( " ", array( $m_icon, $d_icon ) ) ?> atw-text-white"><i class="fas fa-search atw-text-base"></i></span> 
        <span class="<?php echo join( " ", array( $m_text, $d_text ) ) ?>"><?php isset( $args["text"] ) ? esc_html( $args["text"] ) : _e("Search", "wpadverts" ) ?></span>
    </button>
    <?php
}

function wpadverts_get_grays_palette( $gray ) {
    $palettes = apply_filters( "wpadverts_grays_palette", array(
        "blue-gray" => array(
          50 => "#F8FAFC",  100 => "#F1F5F9", 200 => "#E2E8F0", 300 => "#CBD5E1", 400 => "#94A3B8", 500 => "#64748B", 600 => "#475569", 700 => "#334155", 800 => "#1E293B", 900 => "#0F172A"
        ),        
        "cool-gray" => array(
          50 => "#F9FAFB",  100 => "#F3F4F6", 200 => "#E5E7EB", 300 => "#D1D5DB", 400 => "#9CA3AF", 500 => "#6B7280", 600 => "#4B5563", 700 => "#374151", 800 => "#1F2937", 900 => "#111827"
        ),        
        "gray" => array(
          50 => "#FAFAFA",  100 => "#F4F4F5", 200 => "#E4E4E7", 300 => "#D4D4D8", 400 => "#A1A1AA", 500 => "#71717A", 600 => "#52525B", 700 => "#3F3F46", 800 => "#27272A", 900 => "#18181B"
        ),        
        "true-gray" => array(
          50 => "#FAFAFA",  100 => "#F5F5F5", 200 => "#E5E5E5", 300 => "#D4D4D4", 400 => "#A3A3A3", 500 => "#737373", 600 => "#525252", 700 => "#404040", 800 => "#262626", 900 => "#171717"
        ),        
        "warm-gray" => array(
          50 => "#FAFAF9",  100 => "#F5F5F4", 200 => "#E7E5E4", 300 => "#D6D3D1", 400 => "#A8A29E", 500 => "#78716C", 600 => "#57534E", 700 => "#44403C", 800 => "#292524", 900 => "#1C1917"
        ),
    ) );

    return $palettes[ $gray ];
};

function wpadverts_print_grays_variables( $gray ) {
    $grays = wpadverts_get_grays_palette( $gray );

    foreach( $grays as $k => $v ) {
        echo sprintf( "--wpa-color-gray-%d: %s;\r\n", $k, adverts_hex2rgba( $v ) );
    }
}
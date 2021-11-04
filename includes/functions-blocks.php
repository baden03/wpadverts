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

    $_args = array(
        "classes_prepend" => "atw-w-full",
        "classes_append" => ""
    );

    $button_class = "";

    if( isset( $args["type"] ) ) {
        $button_class = sprintf( "wpa-btn-%s", $args["type"] );
    }

    $defaults = array(
        "type"              => isset( $args["type"] ) ? $args["type"] : "",
        "classes"           => "atw-text-base atw-outline-none atw-bg-none atw-border atw-border-solid atw-font-semibold atw-px-4",
        "text"              => isset( $args["text"] ) ? $args["text"] : "",
        "icon"              => isset( $args["icon"] ) ? $args["icon"] : "",
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
    <button class="<?php echo $button_class ?> atw-inline-block hover:atw-bg-none atw-bg-none atw-text-white atw-w-full atw-text-base atw-outline-none atw-border-solid hover:atw-bg-blue-700 atw-font-semibold atw-px-4 atw-py-2 <?php echo "$border_radius $border_width $leading" ?>">
        <span class=" <?php echo join( " ", array( $m_icon, $d_icon ) ) ?> atw-text-white"><i class="fas fa-search atw-text-base"></i></span> 
        <span class="<?php echo join( " ", array( $m_text, $d_text ) ) ?>"><?php isset( $args["text"] ) ? esc_html( $args["text"] ) : _e("Search", "wpadverts" ) ?></span>
    </button>
    <?php
}

function wpadverts_block_button_css( $type, $args ) {

    $color_text     = isset( $args["color_text"] )      ? $args["color_text"]       : null;
    $color_bg       = isset( $args["color_bg"] )        ? $args["color_bg"]         : null;
    $color_border   = isset( $args["color_border"] )    ? $args["color_border"]     : null;
    
    $color_text_h   = isset( $args["color_text_h"] )    ? $args["color_text_h"]     : null;
    $color_bg_h     = isset( $args["color_bg_h"] )      ? $args["color_bg_h"]       : null;
    $color_border_h = isset( $args["color_border_h"] )  ? $args["color_border_h"]   : null;
    
    $button_class = sprintf( "wpa-btn-%s", $type );

    ?>
    <?php echo sprintf( ".%s", $button_class ) ?> {
        color: <?php echo $color_text ?>;
        background-color: <?php echo $color_bg ?>;
        border-color: <?php echo $color_border ?>;
        --wpa-btn-shadow-color: <?php echo $color_border ?>;
    }
    <?php echo sprintf( ".%s", $button_class ) ?> > span > i.fas {
        color: <?php echo $color_text ?>;
    }
    <?php echo sprintf( ".%s:hover", $button_class ) ?> {
        color: <?php echo $color_text_h ?>;
        background-color: <?php echo $color_bg_h ?>;
        border-color: <?php echo $color_border_h ?>;
        --wpa-btn-shadow-color: <?php echo $color_border_h ?>;
    }
    <?php echo sprintf( ".%s:hover", $button_class ) ?> > span > i.fas {
        color: <?php echo $color_text_h ?>;
    }
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

function wpadverts_block_form_styles( $atts ) {
    $form_border = array(
        0 => "wpa-border-none",
        1 => "wpa-border-thin",
        2 => "wpa-border-thick",
        3 => "wpa-border-thick-x2"
    );
    $form_rounded = array(
        0 => "wpa-rounded-none",
        1 => "wpa-rounded-sm",
        2 => "wpa-rounded",
        3 => "wpa-rounded-md",
        4 => "wpa-rounded-lg",
        5 => "wpa-rounded-xl",
        6 => "wpa-rounded-2xl",
    );
    
    $form_styles = join( " ", array(
        isset( $atts["form"]["style"] ) ? $atts["form"]["style"] : "",
        isset( $atts["form"]["shadow"] ) ? $atts["form"]["shadow"] : "",
        isset( $atts["form"]["border"] ) ? $form_border[ $atts["form"]["border"] ] : $form_border[0],
        isset( $atts["form"]["rounded"] ) ? $form_rounded[ $atts["form"]["rounded"] ] : $form_rounded[0],
        "wpa-padding-sm"
    ) );

    return $form_styles;
}

function wpadverts_print_grays_variables( $gray ) {
    $grays = wpadverts_get_grays_palette( $gray );

    foreach( $grays as $k => $v ) {
        echo sprintf( "--wpa-color-gray-%d: %s;\r\n", $k, adverts_hex2rgba( $v ) );
    }
}

function wpadverts_get_object_pattern( $object_id, $pattern ) {
    $patterns = array(
        "pattern__price" => "wpadverts_block_list_price",
        "pattern__location" => null,
        "pattern__post_date" => "wpadverts_block_list_post_date"
    );

    if( isset( $patterns[$pattern] ) && is_callable( $patterns[$pattern] ) ) {
        return call_user_func( $patterns[$pattern], $object_id, $pattern );
    }

    return null;
}

function wpadverts_get_object_value( $object_id, $path ) {
    $advert = get_post( $object_id );

    list( $type, $name ) = explode( "__", $path );

    $value = null;

    if( $type == "default" ) {
        $value = $advert->$name;
    } else if( $type == "date" ) {
        $value = $advert->$name;
    } else if( $type == "meta" ) {
        $value = get_post_meta( $object_id, $name, true );
    } else if( $type == "pattern" ) {
        $value = wpadverts_get_object_pattern( $object_id, $path );
    }

    return $value;
}



function wpadverts_block_tpl_wrap( $post_id, $path, $classes = "") {
    $value = wpadverts_get_object_value( $post_id, $path );

    if( $value ) {
        return sprintf( '<div class="%s">%s</div>', $classes, $value );
    }
}

function wpadverts_block_list_price( $post_id ) {
    $price = get_post_meta( $post_id, "adverts_price", true );
    $price_f = null;

    if( $price ) {
        $price_f = adverts_get_the_price( $post_id, $price );
    } elseif( adverts_config( 'empty_price' ) ) {
        $price_f = adverts_empty_price( $post_id );
    }

    return $price_f;
}

function wpadverts_block_list_post_date( $post_id ) {
    return date_i18n( "d/m/Y", get_post_time( 'U', false, $post_id ) );
}

function wpadverts_block_tpl_field_width( $field ) {
    $arr = array(
        "full" => "atw-w-full",
        "half" => "atw-w-full md:atw-w-2/4",
        "third" => "atw-w-full md:atw-w-1/3",
        "fourth" => "atw-w-full md:atw-w-1/4"
    );

    if( ! isset( $field['meta'] ) || ! isset( $field['meta']['search_type'] ) ) {
        $field_type = "full";
    }  else {
        $field_type = $field['meta']['search_type'];
    }

    return $arr[ $field_type ] . " wpa-w-" . $field_type;
}

function wpadverts_block_img_options( $prop ) {
    $width = array(
        "w-1/12", 
        "w-2/12", 
        "w-3/12", 
        "w-4/12", 
        "w-5/12", 
        "w-6/12", 
        "w-7/12", 
        "w-8/12", 
        "w-9/12", 
        "w-10/12", 
        "w-11/12", 
        "w-12/12", 
    );
    $height = array(
        "atw-h-16", 
        "atw-h-20", 
        "atw-h-24", 
        "atw-h-28", 
        "atw-h-32", 
        "atw-h-36", 
        "atw-h-40", 
        "atw-h-44", 
        "atw-h-48", 
        "atw-h-52", 
        "atw-h-56", 
        "atw-h-60",
        "atw-h-64",
        "atw-h-72",
        "atw-h-80",
        "atw-h-96",
    );
    $fit = array(
        "contain" => "atw-object-contain",
        "cover" => "atw-object-cover",
        "fill" => "atw-object-fill",
        "none" => "atw-object-none",
        "scale-down" => "atw-object-scale-down"
    );

    $props = array(
        "height" => $height,
        "width" => $width,
        "fit" => $fit
    );

    return $props[ $prop ];
}

function wpadverts_block_list_image_list( $post_id, $atts ) {

    $result = new stdClass;
    $image_id = adverts_get_main_image_id( $post_id );
    $image_type = "adverts-list";
    $image = false;

    $classes = array();
    $classes_img = array();

    $default_image_url = null;

    if( isset( $atts["default_image_url"] ) && ! empty( $atts["default_image_url"] ) ) {
        $default_image_url = $atts["default_image_url"];
    }

    if( $image_id ) {
        $image = get_post( $image_id );
    }

    $widths = array( "atw-w-16", "atw-w-20", "atw-w-24", "atw-w-28", "atw-w-32", "atw-w-36", "atw-w-40", "atw-w-44", "atw-w-48", "atw-w-52", "atw-w-56", "atw-w-60" );
    $height = array( "atw-h-16", "atw-h-20", "atw-h-24", "atw-h-28", "atw-h-32", "atw-h-36", "atw-h-40", "atw-h-44", "atw-h-48", "atw-h-52", "atw-h-56", "atw-h-60" );
    $fits = array( 
        "contain" => "atw-object-contain",
        "cover" => "atw-object-cover",
        "fill" => "atw-object-fill",
        "none" => "atw-object-none",
        "scale-down" => "atw-object-scale-down"
    );

    $classes[] = $widths[ $atts["list_img_width"] ];
    $classes[] = $height[ $atts["list_img_height"] ];

    $classes_img[] = $fits[ $atts["list_img_fit"] ];

    $image_type = $atts["list_img_source"];

    $result->image = $image;
    $result->image_id = $image_id;
    $result->image_type = $image_type;
    $result->classes = $classes;
    $result->classes_img = $classes_img;
    $result->default_image_url = $default_image_url;

    return $result;
}

function wpadverts_block_list_image_grid( $post_id, $atts ) {
    $result = new stdClass;

    $image_id = adverts_get_main_image_id( $post_id );
    $image_type = "adverts-list";
    $image = false;

    $classes = array();
    $classes_img = array();

    $default_image_url = null;

    if( isset( $atts["default_image_url"] ) && ! empty( $atts["default_image_url"] ) ) {
        $default_image_url = $atts["default_image_url"];
    }

    if( $image_id ) {
        $image = get_post( $image_id );
    }

    $widths = wpadverts_block_img_options( "width" );
    $height = wpadverts_block_img_options( "height" );    
    $fits = wpadverts_block_img_options( "fit" );

    $classes[] = $height[ $atts["grid_img_height"] ];

    $classes_img[] = $fits[ $atts["grid_img_fit"] ];

    $image_type = $atts["grid_img_source"];

    
    $result->image = $image;
    $result->image_id = $image_id;
    $result->image_type = $image_type;
    $result->classes = $classes;
    $result->classes_img = $classes_img;
    $result->default_image_url = $default_image_url;

    return $result;
}


<?php
    /**
     * Plugin Name: Posts QR Code
     * Description: Display QR Code under every posts
     * Plugin URI: http://github.com/ikamal7/posts-qr-code-wp-plugin/
     * Author: Kamal Hosen
     * Author URI: http://ikamal.me/
     * Version: 1.0.0
     * License: GPL2
     * Text Domain: posts-qrcode
     * Domain Path: /languages/
     */
    
    /**
     * Copyright (C) 2018  Kamal  kamalhosen8920@gmail.com
     *
     * This program is free software; you can redistribute it and/or modify
     * it under the terms of the GNU General Public License, version 2, as
     * published by the Free Software Foundation.
     *
     * This program is distributed in the hope that it will be useful,
     * but WITHOUT ANY WARRANTY; without even the implied warranty of
     * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
     * GNU General Public License for more details.
     *
     * You should have received a copy of the GNU General Public License
     * along with this program; if not, write to the Free Software
     * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
     */
    
    
    function pqrc_load_textdomain() {
        load_plugin_textdomain( 'posts-qrcode', false, dirname( __FILE__ ) . '/languages' );
    }
    
    
    function pqrc_display_qr_code( $content ) {
        $current_post_id    = get_the_ID();
        $current_post_title = get_the_title( $current_post_id );
        $current_post_url   = urlencode_deep( get_the_permalink( $current_post_id ) );
        $current_post_type  = get_post_type( $current_post_id );
        /**
         * Post Type Check
         */
        $excluded_post_types = apply_filters( 'pqrc_excluded_post_types', array() );
        if ( in_array( $current_post_type, $excluded_post_types ) ){
            return $content;
        }
    
        /**
         * Dimension Hook
         *
         */
        $dimension = apply_filters('pqrc_qrcode_img_dimension', '185x185');
    
        /**
         * Image Attributes
         */
        $image_attributes = apply_filters('pqrc_image_attributes', null);
        /**
         * Generate QR Code
         */
        $img_src = sprintf( 'https://api.qrserver.com/v1/create-qr-code/?size=%s&data=%s', $dimension, $current_post_url );
        $content .= sprintf( '<div class="qrcode"><img %s src="%s" alt="%s" /></div>', $image_attributes, $img_src, $current_post_title );
        
        return $content;
    }
    
    add_filter( 'the_content', 'pqrc_display_qr_code' );
    
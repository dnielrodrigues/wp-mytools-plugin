<?php

    /*
     * Link Inicial
     */
    $b[] = array(
        'link' => get_site_url(),
        'txt' => "Início",
    );
    
    /*
     * Pagina de listagem
     */
    if ( is_archive() ) {

        /*
         * Link Final
         */
        $b[] = array(
            'txt' => Lib::getCustomPostTitle($post->post_type)
        );

    /*
     * Pagina filha de listagem
     */
    } elseif ( is_single() ) {

        /*
         * Links
         */
        $b[] = array(
            'link' => get_site_url() . "/" . $post->post_type,
            'txt' => Lib::getPostType($post->post_type)->menu_name
        );
        $b[] = array(
            'txt' => get_the_title()
        );

    /*
     * Pagina unica
     */
    } elseif ( is_page() ) {

        /*
         * Links
         */
        $b[] = array(
            'txt' => get_the_title()
        );

    /*
     * Pagina 404
     */
    } elseif ( is_404() ) {

        /*
         * Links
         */
        $b[] = array(
            'txt' => get_the_title()
        );

    /*
     * Pagina 404
     */
    } elseif ( is_search() ) {

        /*
         * Links
         */
        $b[] = array(
            'txt' => get_the_title()
        );

    }

    /*
     * Trata limite de caracteres
     */
    if ( $maxChar != null ) {
        foreach ($b as $i => $value) {
            if ( strlen ( $value['txt'] ) > $maxChar ) {
                $b[$i]['txt'] = substr ( $value['txt'], 0, $maxChar ) . $limitString;
            }
        }
    }

    /*
     * Trata limite de caracteres - apenas do ultimo item
     */
    if ( $maxFinalChar != null ) {
        if ( strlen ( $b[count($b)-1]['txt'] ) > $maxFinalChar ) {
            $b[count($b)-1]['txt'] = substr ( $b[count($b)-1]['txt'], 0, $maxFinalChar ) . $limitString;
        }
    }

?>
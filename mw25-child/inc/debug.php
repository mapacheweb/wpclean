<?php
/**
 * DEBUG Y TROUBLESHOOTING TEMPORAL
 */
if ( ! defined('ABSPATH') ) exit;

// Función para debug de templates
add_action('template_redirect', function() {
    if (current_user_can('manage_options')) {
        global $post;
        
        if (is_singular('habitacion')) {
            error_log('HABITACION DEBUG: Post ID=' . ($post ? $post->ID : 'none'));
            error_log('HABITACION DEBUG: Post Type=' . ($post ? $post->post_type : 'none'));
            error_log('HABITACION DEBUG: Template should be single-habitacion.php');
        }
        
        if (is_post_type_archive('habitacion')) {
            error_log('HABITACION DEBUG: Archive template should be archive-habitacion.php');
        }
    }
});

// Verificar que el post type esté registrado
add_action('init', function() {
    if (current_user_can('manage_options')) {
        $post_types = get_post_types([], 'names');
        if (in_array('habitacion', $post_types)) {
            error_log('HABITACION DEBUG: Post type "habitacion" está registrado correctamente');
        } else {
            error_log('HABITACION DEBUG: ERROR - Post type "habitacion" NO está registrado');
        }
    }
}, 999);

// Hook para mostrar información en el footer (solo para admins)
add_action('wp_footer', function() {
    if (current_user_can('manage_options') && (is_singular('habitacion') || is_post_type_archive('habitacion'))) {
        global $post;
        echo '<div style="position:fixed;bottom:0;left:0;background:#000;color:#fff;padding:10px;font-size:12px;z-index:9999;">';
        echo 'DEBUG: ';
        if (is_singular('habitacion')) {
            echo 'Single Habitación | Post ID: ' . ($post ? $post->ID : 'none');
        }
        if (is_post_type_archive('habitacion')) {
            echo 'Archive Habitaciones';
        }
        echo '</div>';
    }
});
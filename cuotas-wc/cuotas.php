<?php
/*
Plugin Name: Mostrar cuotas en producto
Plugin URI:  
Description: Este es un plugin para mostrar las cuotas que desees en la pagina del producto y en la card del producto.
Version:     1.0
Author:      Franco 
Author URI:  http://greatewb.com.ar
License:     GPL2
*/

// ESTILOS PARA EL PLUGIN 
function load_custom_wp_admin_style()
{
    wp_enqueue_style('custom_wp_admin_css', plugin_dir_url(__FILE__) . 'style.css');
}

add_action('admin_enqueue_scripts', 'load_custom_wp_admin_style');

// FUNCION PARA EL CALCULO DE CUOTAS 

add_filter('woocommerce_get_price_html', 'change_displayed_sale_price_html', 10, 2);
function change_displayed_sale_price_html($price, $product)
{
    $cuotas = get_option('cant_cuotas');
    $text = get_option('text_cuotas');

    if ($product->is_type('simple')) {

        $regular_price = (float) $product->get_regular_price(); // Regular price
        $sale_price = (float) $product->get_price(); // Active price (the "Sale price" when on-sale)


        $precision = 2; // Max number of decimals

        $cuotapreciosale = round($sale_price / $cuotas, $precision);
        $price .= sprintf(
            __('<br><span style="font-size:15px;color:#d66d50;"> <b>%d</b> %s $%s</span>', 'woocommerce'),
            $cuotas, // El número de cuotas
            esc_html($text), // El texto adicional
            $cuotapreciosale // El precio en cuotas
        );
    }
    return $price;


}


/// CONFIGURACION DE PAGINA


function add_git_commit_page()
{
    add_menu_page(
        'Git Commit History',
        'Configurar cuotas',
        'manage_options',
        'git-commit-page',
        'render_custom_settings_page'
    );


}
add_action('admin_menu', 'add_git_commit_page');


//Page Config

function render_custom_settings_page()
{
    // Procesar el formulario si se envió
    if (isset($_POST['cant_cuotas'])) {
        // Guardar la variable en la base de datos de WordPress
        update_option('cant_cuotas', intval($_POST['cant_cuotas']));
        echo '<div class="updated"><p>Variable saved!</p></div>';
    }
    if (isset($_POST['text_cuotas'])) {
        // Guardar la variable en la base de datos de WordPress
        update_option('text_cuotas', $_POST['text_cuotas']);
        echo '<div class="updated"><p>Variable saved!</p></div>';
    }

    // Obtener el valor guardado de la variable, si existe
    $cant_cuotas = get_option('cant_cuotas');
    $text_cuotas = get_option('text_cuotas');
    ?>
    <div class="wrap">
        <h1>Configuracion de cuotas </h1>
        <form method="post" action="">
            <label for="cant_cuotas">Ingresar la cantidad de cuotas</label>
            <input type="number" id="cant_cuotas" name="cant_cuotas" value="<?php echo esc_attr($cant_cuotas); ?>">
            <input type="submit" name="submit" id="submit" class="button button-primary" value="Save">
        </form>
        <form method="post" action="">
            <label for="text_cuotas">Entrar texto</label>
            <input type="text" id="text_cuotas" name="text_cuotas" value="<?php echo esc_attr($text_cuotas); ?>">
            <input type="submit" name="submit" id="submit" class="button button-primary" value="Save">
        </form>
        <section class="show-page">
            <?php if (!empty($cant_cuotas)): ?>
                <div class="saved-variable">
                    <h2>Numero de cuotas:</h2>
                    <p><?php
                    echo esc_html($cant_cuotas); ?></p>
                </div>
            <?php endif; ?>
            <?php if (!empty($text_cuotas)): ?>
                <div class="saved-variable">
                    <h2>Texto Guardado</h2>
                    <p><?php echo esc_html($text_cuotas); ?></p>
                </div>
            <?php endif; ?>
            <?php if (!empty($text_cuotas) && !empty($text_cuotas)): ?>
                <div class="saved-variable">
                    <h2>Texto a mostrar</h2>
                    <p><?php echo esc_html($cant_cuotas) . ' ' . esc_html($text_cuotas); ?></p>
                </div>
            <?php endif; ?>

        </section>
    </div>
    <?php
}



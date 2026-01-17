<?php

/**
 * ====== KONFIGURASI FOOTPRINT ======
 * Edit isi variabel ini
 */
function neon_get_footprint_template() {
    return "
—
Sumber asli: {{url}}
ID Konten: {{id}}
";
}

/**
 * Generate footprint final
 */
function neon_generate_footprint() {
    $template = neon_get_footprint_template();

    return str_replace(
        ['{{url}}', '{{id}}'],
        [get_permalink(), get_the_ID()],
        $template
    );
}

/**
 * Inject script ke footer
 */
function neon_inject_copy_footprint_script() {
    if (!is_singular()) return;

    $footprint = neon_generate_footprint();
    ?>
    <script>
    document.addEventListener('copy', function (e) {
        const selection = window.getSelection().toString();
        if (!selection) return;

        const footprint = `<?php echo esc_js($footprint); ?>`;
        e.clipboardData.setData('text/plain', selection + footprint);
        e.preventDefault();
    });
    </script>
    <?php
}

add_action('wp_footer', 'neon_inject_copy_footprint_script')

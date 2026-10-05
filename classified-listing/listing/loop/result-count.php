<?php
/**
 * Result Count
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div class="rtcl-result-count">
    <?php
    if ( 1 === $total ) {
        esc_html_e( 'Showing the single result', 'clplace' );
    } elseif ( $total <= $per_page || -1 === $per_page ) {
        /* translators: %d: total results */
        echo esc_html( sprintf( _n( '%d Result', '%d Results', $total, 'clplace' ), absint( $total ) ) );
    } else {
        $first = ( $per_page * $current ) - $per_page + 1;
        $last  = min( $total, $per_page * $current );
        /* translators: 1: first result 2: last result 3: total results */
        echo esc_html( sprintf( _nx( ' %1$d&ndash;%2$d of %3$d Result', '%1$d&ndash;%2$d of %3$d Results', $total, 'with first and last Result', 'clplace' ), absint( $first ), absint( $last ), absint( $total ) ) );
    }
    ?>
</div>

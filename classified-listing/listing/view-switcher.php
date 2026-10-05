<?php
/**
 * View switcher
 *
 * @version     1.5.5
 *
 * @var array $views
 * @var string $current_view
 * @var string $default_view
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( empty( $views ) ) {
	return;
}
?>
<div class="rtcl-view-switcher">
	<?php
	foreach ( $views as $value => $label ) {
		$active = $current_view === $value ? ' active' : '';
		$thIcon = $value === 'grid' ? "large" : $value;
		?>
		<a class="rtcl-view-trigger<?php echo esc_attr( $active ); ?>" data-type="<?php echo esc_attr( $value ); ?>"
		   href="<?php echo esc_url( add_query_arg( 'view', $value ) ) ?>">
			<?php if ( 'grid' === $value ): ?>
                <svg width="18" height="18" viewBox="0 0 18 18" fill="black" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0 0H3V3H0V0ZM7.5 0H10.5V3H7.5V0ZM15 0H18V3H15V0ZM0 7.5H3V10.5H0V7.5ZM7.5 7.5H10.5V10.5H7.5V7.5ZM15 7.5H18V10.5H15V7.5ZM0 15H3V18H0V15ZM7.5 15H10.5V18H7.5V15ZM15 15H18V18H15V15Z" fill=""/>
                </svg>
			<?php else: ?>
                <svg width="23" height="15" viewBox="0 0 23 15" fill="#050608" xmlns="http://www.w3.org/2000/svg">
                    <path d="M22.5 0V2.5H0V0H22.5ZM0 15H22.5V12.5H0V15ZM0 8.75H22.5V6.25H0V8.75Z" fill=""/>
                </svg>
			<?php endif; ?>
		</a>
	<?php } ?>
</div>

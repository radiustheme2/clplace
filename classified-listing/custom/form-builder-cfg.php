<?php
/**
 * This file is for showing listing header
 *
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Rtcl\Models\Form\Form;
use Rtcl\Helpers\Functions;
use Rtcl\Services\FormBuilder\FBField;
use Rtcl\Services\FormBuilder\FBHelper;
global $listing;

$listing_form = $listing->getForm();
$sections = $listing_form->getSections();
$sectionTitles = [];
$SectionsFields = [];
$field = $value = '';

foreach ($sections as $section) {
	if (isset($section['id']) && strpos($section['id'], 'section_') !== false) {
		$SectionsFields[$section['id']] = $section['columns'][0]['fields'];
		$sectionTitles['title_'.$section['id']] = $section['title'];
	}
}
foreach ($SectionsFields as $section_id => $fields) {
	foreach ( $fields as $field_id ) {
		$field = $listing_form->getFieldByUuid( $field_id );
		$field = new FBField( $field );
		$value = $field->getFormattedCustomFieldValue( $listing->get_id() );
	}
}

foreach ($SectionsFields as $section_id => $fields) {
	if (!empty($value)){
	?>
    <div class="form-section-box ">
        <h3 class="desc-title"><?php echo esc_html( stripslashes( $sectionTitles['title_'.$section_id] ) ); ?></h3>
        <ul class="section-elements">
			<?php
			foreach ($fields as $field_id) {
				$field = $listing_form->getFieldByUuid($field_id);
				$field = new FBField( $field );
				$value = $field->getFormattedCustomFieldValue( $listing->get_id() );
				$label = $field->getLabel();
				$name = $field->getName();
				$search = [' ', ',', '.', '!', '?', '"', ';', ':'];
				$replace = '-';
				$modified_string = strtolower(str_replace($search, $replace, $label));
				$field_name = strtolower(str_replace($search, $replace, $name));
				$options = $field->getOptions();
				if ( !empty( $value ) ) { ?>
                    <li class="rtcl-cf-<?php echo esc_attr( $field->getElement().' label-'.$modified_string.' '.$field_name ) ?>">
						<?php if ( $field->getElement() === 'url' ) {
							$nofollow = !empty( $field->getNofollow() ) ? ' rel="nofollow"' : ''; ?>
                            <div class="cfp-label"><span><?php echo esc_html( $field->getLabel() ) ?></span></div>
                            <div class="cfp-value">
                                <a href="<?php echo esc_url( $value ); ?>"
                                   target="<?php echo esc_attr( $field->getTarget() ) ?>"<?php echo esc_html( $nofollow ) ?>><?php echo esc_url( $value ); ?></a>
                            </div>
						<?php } else { ?>
                            <div class="cfp-label"><span><?php echo esc_html( $field->getLabel() ) ?></span></div>
                            <div class="cfp-value">
								<?php if ( $field->getElement() === 'color_picker' ) { ?>
                                    <span class="cfp-color"
                                          style="width:20px; height:20px; display:inline-block;background-color: <?php echo esc_attr( $value ) ?>;"></span>
								<?php } elseif ( in_array( $field->getElement(), [ 'select', 'radio', 'checkbox' ] ) ) {
									if ( $field->getElement() === 'checkbox' ) {
										$enable_icon_class = $field->getData( 'enable_icon_class', false );
										ob_start();
										echo "<ul class='multi-checkbox-values'>";
										foreach ( $options as $option ) {
											if ( !empty( $option['value'] ) && in_array( $option['value'], $value ) ) { ?>
                                                <li>
													<?php
													if ( ! empty( $option['icon_class']) && $enable_icon_class ) { ?>
                                                        <i class="<?php echo esc_attr( $option['icon_class'] ); ?>"></i>
													<?php } ?>
													<?php echo esc_html( $option['label'] ); ?>
                                                </li>
											<?php }
										}
										echo "</ul>";
										$value = ob_get_clean();
									} else {
										$value = is_array( $value ) && !empty( $value['label'] ) ? $value['label'] : '';
									}
									Functions::print_html( $value );
								} elseif ( $field->getElement() === 'html' ) {
									echo esc_html( $value );
								} elseif ( $field->getElement() === 'file' ) {
									if ( !empty( $value ) && is_array( $value ) ) {
										foreach ( $value as $file ) {
											if ( empty( $file['url'] ) || empty( $file['name'] ) ) {
												continue;
											}
											$ext = pathinfo( $file['url'], PATHINFO_EXTENSION );
											if ( $ext == 'pdf' ) {
												$iconClass = 'rtcl-icon-file-pdf';
											} elseif ( in_array( $ext, [ 'avi', 'divx', 'flv', 'mov', 'ogv', 'mkv', 'mp4', 'm4v', 'divx', 'mpg', 'mpeg', 'mpe' ] ) ) {
												$iconClass = 'rtcl-icon-music';
											} elseif ( in_array( $ext, [ 'mp3', 'wav', 'ogg', 'oga', 'wma', 'mka', 'm4a', 'ra', 'mid', 'midi' ] ) ) {
												$iconClass = 'rtcl-icon-music';
											} elseif ( in_array( $ext, [ 'zip', 'gz', 'gzip', 'rar', '7z' ] ) ) {
												$iconClass = 'rtcl-icon-file-archive';
											} elseif ( in_array( $ext, [ 'jpg', 'jpeg', 'gif', 'png', 'bmp' ] ) ) {
												$iconClass = 'rtcl-icon-file-archive';
											} elseif ( in_array( $ext, [ 'doc', 'ppt', 'pps', 'xls', 'mdb', 'docx', 'xlsx', 'pptx', 'odt', 'odp', 'ods', 'odg', 'odc', 'odb', 'odf', 'rtf', 'txt', 'csv' ] ) ) {
												$iconClass = 'rtcl-icon-doc';
											} else {
												$iconClass = 'rtcl-icon-attach';
											}

											?>
                                            <div class="rtcl-file-item">
												<?php if ( in_array( $ext, [ 'jpg', 'jpeg', 'gif', 'png', 'bmp' ] ) ) { ?>
                                                    <img src="<?php echo esc_url( $file['url'] ) ?>" alt="">
												<?php } else { ?>
                                                    <i class="rtcl-icon <?php echo esc_attr( $iconClass ); ?>"></i>
                                                    <a href="<?php echo esc_url( $file['url'] ) ?>" target="_blank">
														<?php echo esc_html( $file['name'] ) ?>
                                                    </a>
												<?php } ?>
                                            </div>
											<?php
										}
									}
								} else {
									if ( 'repeater' === $field->getElement() ){
										$repeaterFields = $field->getData( 'fields', [] );
										if ( ! empty( $repeaterFields ) && is_array( $value ) ) { ?>
                                            <div class="cfp-repeater-items">
												<?php foreach ( $value as $rValueIndex => $rValues ) { ?>
                                                    <div class="cfp-repeater-item">
														<?php
														foreach ( $repeaterFields as $repeaterField ) {
															$rField = new FBField( $repeaterField );
															$rValue  = $rValues[ $rField->getName() ] ?? '';
															?>
                                                            <div class="cfp-repeater-field">
                                                                <div class="cfp-label">
																	<?php
																	$rIcon = $rField->getIconData();
																	if ( ! empty( $rIcon['type'] ) && 'class' === $rIcon['type'] && ! empty( $rIcon['class'] ) ) {
																		?>
                                                                        <div class="rtcl-field-icon">
                                                                            <i class="<?php echo esc_attr( $rIcon['class'] ); ?>"></i>
                                                                        </div>
																	<?php } ?>
                                                                    <span><?php echo esc_html( $rField->getLabel() ); ?></span>:
                                                                </div>
                                                                <div class="cfp-value">
																	<?php Functions::print_html( FBHelper::getFormattedFieldHtml( $rValue, $rField ) ); ?>
                                                                </div>
                                                            </div>
															<?php
														}
														?>
                                                    </div>
													<?php
												} ?>
                                            </div>
										<?php }
									} else {
										Functions::print_html( FBHelper::getFormattedFieldHtml( $value, $field ) );
									}
								} ?>
                            </div>
						<?php } ?>
                    </li>
				<?php }
			}
			?>
        </ul>
    </div>
	<?php
	}
}
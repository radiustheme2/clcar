<?php
/**
 * Listing form section renderer (no getFormId() required)
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Rtcl\Helpers\Functions;
use Rtcl\Services\FormBuilder\FBField;
use Rtcl\Services\FormBuilder\FBHelper;

global $listing;

$listing_form = $listing->getForm();

// ====== OPTIONAL: CHOOSE SECTION(S) HERE ======
// One Section
 $only_section_id = 'car_details_fields';

// One more sections
 $only_section_ids = ['car_details_fields', 'car_features_fields'];
// ============================================

// normalize selected ids
$__only_sections = [];
if ( isset( $only_section_id ) && $only_section_id ) {
	$__only_sections[] = (string) $only_section_id;
}
if ( isset( $only_section_ids ) && is_array( $only_section_ids ) ) {
	foreach ( $only_section_ids as $sid ) {
		$sid = (string) $sid;
		if ( $sid !== '' ) {
			$__only_sections[] = $sid;
		}
	}
}
$__only_sections = array_values( array_unique( array_filter( $__only_sections ) ) );

if ( ! empty( $listing_form ) ) {
	$sections       = $listing_form->getSections();
	$sectionTitles  = [];
	$SectionsFields = [];
	$sectionPrefix  = '';

	// collect target sections (respect $__only_sections if provided)
	foreach ( $sections as $section ) {
		if ( isset( $section['id'] ) ) {

			// If filter provided, skip other sections
			if ( ! empty( $__only_sections ) && ! in_array( $section['id'], $__only_sections, true ) ) {
				continue;
			}

			$SectionsFields[ $section['id'] ]             = $section['columns'][0]['fields'] ?? [];
			$sectionTitles[ 'title_' . $section['id'] ]   = $section['title'] ?? '';
			$sectionPrefix                                = 'section_';
		}
	}

	if ( $sectionPrefix === 'section_' ) {

		foreach ( $SectionsFields as $section_id => $fields ) {

			// safety: again ensure only requested sections are rendered
			if ( ! empty( $__only_sections ) && ! in_array( $section_id, $__only_sections, true ) ) {
				continue;
			}

			ob_start();

			foreach ( $fields as $field_id ) {
				// get field definition from form (UUID)
				$field_def = $listing_form->getFieldByUuid( $field_id );

				// instantiate FBField using just the field data
				$field_obj = new FBField( $field_def );

				// If field object not valid or not viewable in single view, skip
				if ( ! $field_obj || ! method_exists( $field_obj, 'isSingleViewAble' ) || ! $field_obj->isSingleViewAble() ) {
					continue;
				}

				// If FBField provides a frontend renderer, prefer it
				if ( method_exists( $field_obj, 'renderFrontendView' ) ) {
					// render directly (assumed safe / escaped inside method)
					$field_obj->renderFrontendView( $listing );
					continue;
				}

				// Fallback: manually build output using helper methods
				$value  = method_exists( $field_obj, 'getFormattedCustomFieldValue' ) ? $field_obj->getFormattedCustomFieldValue( $listing->get_id() ) : '';
				$label  = method_exists( $field_obj, 'getLabel' ) ? $field_obj->getLabel() : '';
				$element = method_exists( $field_obj, 'getElement' ) ? $field_obj->getElement() : 'text';
				$options = method_exists( $field_obj, 'getOptions' ) ? $field_obj->getOptions() : [];
				$enable_icon_class = method_exists( $field_obj, 'getData' ) ? $field_obj->getData( 'enable_icon_class', false ) : false;

				// prepare safe css-friendly label slug
				$search = [ ' ', ',', '.', '!', '?', '"', ';', ':' ];
				$replace = '-';
				$modified_string = strtolower( str_replace( $search, $replace, $label ) );

				if ( empty( $value ) ) {
					continue;
				}

				// Output markup (similar to your previous logic)
				?>
                <li class="rtcl-cf-<?php echo esc_attr( $element . ' label-' . $modified_string ); ?>">
					<?php if ( 'url' === $element ) :
						$nofollow = method_exists( $field_obj, 'getNofollow' ) && $field_obj->getNofollow() ? ' rel="nofollow"' : ''; ?>
                        <div class="cfp-label"><span><?php echo esc_html( $label ); ?></span></div>
                        <div class="cfp-value">
                            <a href="<?php echo esc_url( $value ); ?>" target="<?php echo esc_attr( method_exists( $field_obj, 'getTarget' ) ? $field_obj->getTarget() : '_blank' ); ?>"<?php echo esc_attr( $nofollow ); ?>>
								<?php echo esc_url( $value ); ?>
                            </a>
                        </div>
					<?php else : ?>
                        <div class="cfp-label"><span><?php echo esc_html( $label ); ?></span></div>
                        <div class="cfp-value">
							<?php
							if ( 'color_picker' === $element ) {
								echo '<span class="cfp-color" style="width:20px; height:20px; display:inline-block; background-color: ' . esc_attr( $value ) . ';"></span>';
							} elseif ( 'checkbox' === $element ) {
								if ( is_array( $value ) && ! empty( $value ) ) {
									$items = [];
									foreach ( $options as $option ) {
										if ( ! empty( $option['value'] ) && in_array( $option['value'], $value, true ) ) {
											$icon = ( ! empty( $option['icon_class'] ) && $enable_icon_class ) ? '<i class="' . esc_attr( $option['icon_class'] ) . '"></i>' : '';
											$items[] = sprintf( '<span class="rtcl-cfp-vi">%s%s</span>', $icon, esc_html( $option['label'] ) );
										}
									}
									Functions::print_html( ! empty( $items ) ? implode( ' ', $items ) : '' );
								}
							} elseif ( 'html' === $element ) {
								// html is assumed safe/trusted by builder
								echo wp_kses_post( $value );
							} elseif ( 'file' === $element && is_array( $value ) ) {
								foreach ( $value as $file ) {
									if ( empty( $file['url'] ) || empty( $file['name'] ) ) {
										continue;
									}
									$ext = pathinfo( $file['url'], PATHINFO_EXTENSION );
									if ( $ext === 'pdf' ) {
										$iconClass = 'rtcl-icon-file-pdf';
									} elseif ( in_array( $ext, [ 'avi','divx','flv','mov','ogv','mkv','mp4','m4v','mpg','mpeg' ], true ) ) {
										$iconClass = 'rtcl-icon-music';
									} elseif ( in_array( $ext, [ 'mp3','wav','ogg','oga','wma','mka','m4a' ], true ) ) {
										$iconClass = 'rtcl-icon-music';
									} elseif ( in_array( $ext, [ 'zip','gz','rar','7z' ], true ) ) {
										$iconClass = 'rtcl-icon-file-archive';
									} elseif ( in_array( $ext, [ 'jpg','jpeg','gif','png','bmp' ], true ) ) {
										$iconClass = 'rtcl-icon-file-archive';
									} else {
										$iconClass = 'rtcl-icon-attach';
									}
									?>
                                    <div class="rtcl-file-item">
										<?php if ( in_array( $ext, [ 'jpg','jpeg','gif','png','bmp' ], true ) ) : ?>
                                            <img src="<?php echo esc_url( $file['url'] ); ?>" alt="">
										<?php else : ?>
                                            <i class="rtcl-icon <?php echo esc_attr( $iconClass ); ?>"></i>
                                            <a href="<?php echo esc_url( $file['url'] ); ?>" target="_blank"><?php echo esc_html( $file['name'] ); ?></a>
										<?php endif; ?>
                                    </div>
									<?php
								}
							} elseif ( 'repeater' === $element ) {
								$repeaterFields = $field_obj->getData( 'fields', [] );
								if ( ! empty( $repeaterFields ) && is_array( $value ) ) {
									echo '<div class="cfp-repeater-items">';
									foreach ( $value as $rValues ) {
										echo '<div class="cfp-repeater-item">';
										foreach ( $repeaterFields as $repeaterField ) {
											$rField = new FBField( $repeaterField );
											$rValue = $rValues[ $rField->getName() ] ?? '';
											?>
                                            <div class="cfp-repeater-field">
                                                <div class="cfp-label">
													<?php
													$rIcon = $rField->getIconData();
													if ( ! empty( $rIcon['type'] ) && 'class' === $rIcon['type'] && ! empty( $rIcon['class'] ) ) {
														echo '<div class="rtcl-field-icon"><i class="' . esc_attr( $rIcon['class'] ) . '"></i></div>';
													}
													?>
                                                    <span><?php echo esc_html( $rField->getLabel() ); ?></span>:
                                                </div>
                                                <div class="cfp-value">
													<?php Functions::print_html( FBHelper::getFormattedFieldHtml( $rValue, $rField ) ); ?>
                                                </div>
                                            </div>
											<?php
										}
										echo '</div>';
									}
									echo '</div>';
								}
							} else {
								Functions::print_html( FBHelper::getFormattedFieldHtml( $value, $field_obj ) );
							}
							?>
                        </div>
					<?php endif; ?>
                </li>
				<?php
			}

			$listItemHtml = ob_get_clean();

			if ( $listItemHtml ) { ?>
                <div class="form-section-box">
                    <h3><?php echo esc_html( $sectionTitles[ 'title_' . $section_id ] ); ?></h3>
                    <ul class="section-elements">
						<?php Functions::print_html( $listItemHtml ); ?>
                    </ul>
                </div>
				<?php
			}
		}
	} else {
		$listing->custom_fields();
	}
}

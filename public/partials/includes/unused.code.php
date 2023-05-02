<table id="product-templates" class="display" cellspacing="0" width="100%">
					<thead>
						<tr>
						  <th>
								<?php if( WCFM_Dependencies::wcfmu_plugin_active_check() ) { ?>
									<?php if( apply_filters( 'wcfm_is_allow_bulk_edit', true ) ) { ?><input type="checkbox" class="wcfm-checkbox bulk_action_checkbox_all text_tip" name="bulk_action_checkbox_all_top" value="yes" data-tip="<?php _e( 'Select all for bulk edit', 'wc-frontend-manager' ); ?>" /><?php } ?>
							  <?php } elseif( apply_filters( 'is_wcfmu_inactive_notice_show', true ) ) { ?>
							  	<input type="checkbox" class="wcfm-checkbox bulk_action_checkbox_all text_tip" onclick="return false;" name="bulk_action_checkbox_all_top" value="yes" data-tip="<?php wcfmu_feature_help_text_show( 'Bulk Edit', false, true ); ?>" />
							  <?php } ?>
						  </th>
							<th><span class="wcfmfa fa-image text_tip" data-tip="<?php _e( 'Image', 'wc-frontend-manager' ); ?>"></span></th>
							<th style="max-width: 250px;"><?php _e( 'Name', 'wc-frontend-manager' ); ?></th>
							<th><?php _e( 'SKU', 'wc-frontend-manager' ); ?></th>
							<th><?php _e( 'Status', 'wc-frontend-manager' ); ?></th>
							<th><?php _e( 'Stock', 'wc-frontend-manager' ); ?></th>
							<th><?php _e( 'Price', 'wc-frontend-manager' ); ?></th>
							<th><?php _e( 'Taxonomies', 'wc-frontend-manager' ); ?></th>
							<th><span class="wcfmfa fa-cubes text_tip" data-tip="<?php _e( 'Type', 'wc-frontend-manager' ); ?>"></span></th>
							<th><span class="wcfmfa fa-eye text_tip" data-tip="<?php _e( 'Views', 'wc-frontend-manager' ); ?>"></span></th>
							<th><?php _e( 'Printable SKU', 'wc-frontend-manager' ); ?></th>
							<th><?php _e( 'Min Price', 'wc-frontend-manager' ); ?></th>
							<th><?php _e( apply_filters( 'wcfm_products_additional_info_column_label', __( 'Additional Info', 'wc-frontend-manager' ) ) ); ?></th>
							<th><?php _e( 'Actions', 'wc-frontend-manager' ); ?></th>
						</tr>
					</thead>
					<tfoot>
						<tr>
						  <th>
								<?php if( WCFM_Dependencies::wcfmu_plugin_active_check() ) { ?>
									<?php if( apply_filters( 'wcfm_is_allow_bulk_edit', true ) ) { ?><input type="checkbox" class="wcfm-checkbox bulk_action_checkbox_all text_tip" name="bulk_action_checkbox_all_top" value="yes" data-tip="<?php _e( 'Select all for bulk edit', 'wc-frontend-manager' ); ?>" /><?php } ?>
							  <?php } elseif( apply_filters( 'is_wcfmu_inactive_notice_show', true ) ) { ?>
							  	<input type="checkbox" class="wcfm-checkbox bulk_action_checkbox_all text_tip" onclick="return false;" name="bulk_action_checkbox_all_top" value="yes" data-tip="<?php wcfmu_feature_help_text_show( 'Bulk Edit', false, true ); ?>" />
							  <?php } ?>
						  </th>
							<th><span class="wcfmfa fa-image text_tip" data-tip="<?php _e( 'Image', 'wc-frontend-manager' ); ?>"></span></th>
							<th style="max-width: 250px;"><?php _e( 'Name', 'wc-frontend-manager' ); ?></th>
							<th><?php _e( 'SKU', 'wc-frontend-manager' ); ?></th>
							<th><?php _e( 'Status', 'wc-frontend-manager' ); ?></th>
							<th><?php _e( 'Stock', 'wc-frontend-manager' ); ?></th>
							<th><?php _e( 'Price', 'wc-frontend-manager' ); ?></th>
							<th><?php _e( 'Taxonomies', 'wc-frontend-manager' ); ?></th>
							<th><span class="wcfmfa fa-cubes text_tip" data-tip="<?php _e( 'Type', 'wc-frontend-manager' ); ?>"></span></th>
							<th><span class="wcfmfa fa-eye text_tip" data-tip="<?php _e( 'Views', 'wc-frontend-manager' ); ?>"></span></th>
							<th><?php _e( 'Printable SKU', 'wc-frontend-manager' ); ?></th>
							<th><?php _e( 'Min Price', 'wc-frontend-manager' ); ?></th>
							<th><?php _e( apply_filters( 'wcfm_products_additional_info_column_label', __( 'Additional Info', 'wc-frontend-manager' ) ) ); ?></th>
							<th><?php _e( 'Actions', 'wc-frontend-manager' ); ?></th>
						</tr>
					</tfoot>
				</table>
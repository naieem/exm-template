<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<?php do_action( 'wpo_wcpdf_before_document', $this->get_type(), $this->order ); ?>

<table class="head container">
  <tbody>
    <tr>
      <td class="header" rowspan="2">
      <?php
		if ( $this->has_header_logo() ) {
			do_action( 'wpo_wcpdf_before_shop_logo', $this->get_type(), $this->order );
			$this->header_logo();
			do_action( 'wpo_wcpdf_after_shop_logo', $this->get_type(), $this->order );
		} else {
			echo $this->get_title();
		}
      ?>  
      </td>
      <td colspan="2" class="exm-edi-invoice-title"><h1>Web Order<br/>Commande Web</h1></td>
    </tr>
    <tr>
      <td width="30%"><span class="exm-edi-subtitles">Order/Commande:<br/>Date:<br/>Payment/Paiement:<br/>Client:</span></td>
      <td width="70%"><span class="exm-edi-header-info"><?php $this->order_number(); ?><br/><?php $this->order_date(); ?><br/>Credit card prepaid/Carte de crédit prépayé<br/>
          <?php
          $shipping_state = $order->get_shipping_state();
          if($shipping_state == 'QC'){
              $exm_client_number = 'EC1000';
          }
          elseif($shipping_state == 'ON'){
              $exm_client_number = 'EC2000';
          }
          elseif($shipping_state = array("AB", "BC", "MB", "SK")){
              $exm_client_number = 'EC3000';
          }
          elseif($shipping_state = array("PE", "NB", "NS", "NL")){
              $exm_client_number = 'EC4000';
          }
          else{
              $exm_client_number = 'OTHER';
          }
          echo $exm_client_number;
          ?></span></td>
    </tr>
  </tbody>
</table>

<!--<table class="head container">
	<tr>
		<td class="header">
		<?php /*
		if ( $this->has_header_logo() ) {
			do_action( 'wpo_wcpdf_before_shop_logo', $this->get_type(), $this->order );
			$this->header_logo();
			do_action( 'wpo_wcpdf_after_shop_logo', $this->get_type(), $this->order );
		} else {
			echo $this->get_title();
		}
		?>
		</td>
		<td class="shop-info">
			<?php do_action( 'wpo_wcpdf_before_shop_name', $this->get_type(), $this->order ); ?>
			<div class="shop-name"><h3><?php $this->shop_name(); ?></h3></div>
			<?php do_action( 'wpo_wcpdf_after_shop_name', $this->get_type(), $this->order ); ?>
			<?php do_action( 'wpo_wcpdf_before_shop_address', $this->get_type(), $this->order ); ?>
			<div class="shop-address"><?php $this->shop_address(); ?></div>
			<?php do_action( 'wpo_wcpdf_after_shop_address', $this->get_type(), $this->order ); */ ?>
		</td>
	</tr>
</table>-->

<?php do_action( 'wpo_wcpdf_before_document_label', $this->get_type(), $this->order ); ?>

<!--<h1 class="document-type-label">
	<?php // if ( $this->has_header_logo() ) echo $this->get_title(); ?>
</h1>-->

<?php do_action( 'wpo_wcpdf_after_document_label', $this->get_type(), $this->order ); ?>

<table class="order-data-addresses">
	<tr>
		<td class="address billing-address">
			<!-- <h3><?php //_e( 'Billing Address:', 'woocommerce-pdf-invoices-packing-slips' ); ?></h3> -->
            <strong>Billed to/Facturé à:</strong><br/>
			<?php do_action( 'wpo_wcpdf_before_billing_address', $this->get_type(), $this->order ); ?>
			<?php $this->billing_address(); ?>
			<?php do_action( 'wpo_wcpdf_after_billing_address', $this->get_type(), $this->order ); ?>
			<?php if ( isset( $this->settings['display_email'] ) ) : ?>
				<div class="billing-email"><?php $this->billing_email(); ?></div>
			<?php endif; ?>
			<?php if ( isset( $this->settings['display_phone'] ) ) : ?>
				<div class="billing-phone"><?php $this->billing_phone(); ?></div>
			<?php endif; ?>
		</td>
		<td class="address shipping-address">
			<?php //if ( $this->show_shipping_address() ) : ?>
				<!--<h3><?php // _e( 'Ship To:', 'woocommerce-pdf-invoices-packing-slips' ); ?></h3>-->
                <strong>Ship to/Envoyer à:</strong><br/>
				<?php do_action( 'wpo_wcpdf_before_shipping_address', $this->get_type(), $this->order ); ?>
				<?php $this->shipping_address(); ?>
				<?php do_action( 'wpo_wcpdf_after_shipping_address', $this->get_type(), $this->order ); ?>
				<?php if ( isset( $this->settings['display_phone'] ) ) : ?>
					<div class="shipping-phone"><?php $this->shipping_phone(); ?></div>
				<?php endif; ?>
			<?php //endif; ?>
		</td>
		<td class="order-data">
			<!--<table>
				<?php /* do_action( 'wpo_wcpdf_before_order_data', $this->get_type(), $this->order ); ?>
				<?php if ( isset( $this->settings['display_number'] ) ) : ?>
					<tr class="invoice-number">
						<th><?php echo $this->get_number_title(); ?></th>
						<td><?php $this->invoice_number(); ?></td>
					</tr>
				<?php endif; ?>
				<?php if ( isset( $this->settings['display_date'] ) ) : ?>
					<tr class="invoice-date">
						<th><?php echo $this->get_date_title(); ?></th>
						<td><?php $this->invoice_date(); ?></td>
					</tr>
				<?php endif; ?>
				<tr class="order-number">
					<th><?php _e( 'Order Number:', 'woocommerce-pdf-invoices-packing-slips' ); ?></th>
					<td><?php $this->order_number(); ?></td>
				</tr>
				<tr class="order-date">
					<th><?php _e( 'Order Date:', 'woocommerce-pdf-invoices-packing-slips' ); ?></th>
					<td><?php $this->order_date(); ?></td>
				</tr>
				<?php if ( $payment_method = $this->get_payment_method() ) : ?>
				<tr class="payment-method">
					<th><?php _e( 'Payment Method:', 'woocommerce-pdf-invoices-packing-slips' ); ?></th>
					<td><?php echo $payment_method; ?></td>
				</tr>
				<?php endif; ?>
				<?php do_action( 'wpo_wcpdf_after_order_data', $this->get_type(), $this->order ); */ ?>
			</table>-->			
		</td>
	</tr>
</table>

<table class="exm-edi-extra-ship-info">
    <tr>
		<td style="width:35%;"><span class="exm-edi-subtitles">Shipped via/Expédié via:<br/>Shipping terms/Conditions d'expédition:</span></td>
		<td style="width:30%;"><span class="exm-edi-header-info">
            <?php
            foreach( $order->get_items( 'shipping' ) as $item_id => $item ){
                $exm_ship_method = $item->get_method_title();
            }
            echo $exm_ship_method;
            ?>
            <br/>FOB/F.A.B.</span></td>
	</tr>
</table>

<?php do_action( 'wpo_wcpdf_before_order_details', $this->get_type(), $this->order ); ?>

<table class="order-details">
	<thead>
		<tr>
			<th class="quantity">Quantity<br/>Quantité</th>
            <th class="sku">Product number<br/>Numéro de produit</th>
            <th class="product">Description</th>
            <th class="unit-price"><span style="display:block;width:100%;text-align:right;">Unit/Unité</span></th>
			<th class="price"><span style="display:block;width:100%;text-align:right;">Price/Prix</span></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ( $this->get_order_items() as $item_id => $item ) : ?>
			<tr class="<?php echo apply_filters( 'wpo_wcpdf_item_row_class', 'item-'.$item_id, $this->get_type(), $this->order, $item_id ); ?>">
				<td class="quantity"><?php echo $item['quantity']; ?></td>
                <td class="sku"><?php echo $item['sku']; ?></td>
                <td class="product">
					<span class="item-name"><?php echo $item['name']; ?></span>
				</td>
				<td class="unit-price"><?php echo $item['single_line_total']; ?></td>
				<td class="price"><?php echo $item['order_price']; ?></td>
			</tr>
		<?php endforeach; ?>
	</tbody>
	<tfoot>
		<tr class="no-borders">
			<td class="no-borders">
				<div class="document-notes">
					<?php do_action( 'wpo_wcpdf_before_document_notes', $this->get_type(), $this->order ); ?>
					<?php if ( $this->get_document_notes() ) : ?>
						<h3><?php _e( 'Notes', 'woocommerce-pdf-invoices-packing-slips' ); ?></h3>
						<?php $this->document_notes(); ?>
					<?php endif; ?>
					<?php do_action( 'wpo_wcpdf_after_document_notes', $this->get_type(), $this->order ); ?>
				</div>
				<div class="customer-notes">
					<?php do_action( 'wpo_wcpdf_before_customer_notes', $this->get_type(), $this->order ); ?>
					<?php if ( $this->get_shipping_notes() ) : ?>
						<h3><?php _e( 'Customer Notes', 'woocommerce-pdf-invoices-packing-slips' ); ?></h3>
						<?php $this->shipping_notes(); ?>
					<?php endif; ?>
					<?php do_action( 'wpo_wcpdf_after_customer_notes', $this->get_type(), $this->order ); ?>
				</div>				
			</td>
			<td class="no-borders" colspan="4">
				<table class="totals">
					<tfoot>
						<?php foreach ( $this->get_woocommerce_totals() as $key => $total ) : ?>
							<tr class="<?php echo $key; ?>">
								<th class="description"><?php echo $total['label']; ?></th>
								<td class="price"><span class="totals-price"><?php echo $total['value']; ?></span></td>
							</tr>
						<?php endforeach; ?>
					</tfoot>
				</table>
			</td>
		</tr>
	</tfoot>
</table>

<!--<table class="order-details">
	<thead>
		<tr>
			<th class="product"><?php /* _e( 'Product', 'woocommerce-pdf-invoices-packing-slips' ); ?></th>
			<th class="quantity"><?php _e( 'Quantity', 'woocommerce-pdf-invoices-packing-slips' ); ?></th>
			<th class="price"><?php _e( 'Price', 'woocommerce-pdf-invoices-packing-slips' ); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ( $this->get_order_items() as $item_id => $item ) : ?>
			<tr class="<?php echo apply_filters( 'wpo_wcpdf_item_row_class', 'item-'.$item_id, $this->get_type(), $this->order, $item_id ); ?>">
				<td class="product">
					<?php $description_label = __( 'Description', 'woocommerce-pdf-invoices-packing-slips' ); // registering alternate label translation ?>
					<span class="item-name"><?php echo $item['name']; ?></span>
					<?php do_action( 'wpo_wcpdf_before_item_meta', $this->get_type(), $item, $this->order  ); ?>
					<span class="item-meta"><?php echo $item['meta']; ?></span>
					<dl class="meta">
						<?php $description_label = __( 'SKU', 'woocommerce-pdf-invoices-packing-slips' ); // registering alternate label translation ?>
						<?php if ( ! empty( $item['sku'] ) ) : ?><dt class="sku"><?php _e( 'SKU:', 'woocommerce-pdf-invoices-packing-slips' ); ?></dt><dd class="sku"><?php echo $item['sku']; ?></dd><?php endif; ?>
						<?php if ( ! empty( $item['weight'] ) ) : ?><dt class="weight"><?php _e( 'Weight:', 'woocommerce-pdf-invoices-packing-slips' ); ?></dt><dd class="weight"><?php echo $item['weight']; ?><?php echo get_option( 'woocommerce_weight_unit' ); ?></dd><?php endif; ?>
					</dl>
					<?php do_action( 'wpo_wcpdf_after_item_meta', $this->get_type(), $item, $this->order  ); ?>
				</td>
				<td class="quantity"><?php echo $item['quantity']; ?></td>
				<td class="price"><?php echo $item['order_price']; ?></td>
			</tr>
		<?php endforeach; ?>
	</tbody>
	<tfoot>
		<tr class="no-borders">
			<td class="no-borders">
				<div class="document-notes">
					<?php do_action( 'wpo_wcpdf_before_document_notes', $this->get_type(), $this->order ); ?>
					<?php if ( $this->get_document_notes() ) : ?>
						<h3><?php _e( 'Notes', 'woocommerce-pdf-invoices-packing-slips' ); ?></h3>
						<?php $this->document_notes(); ?>
					<?php endif; ?>
					<?php do_action( 'wpo_wcpdf_after_document_notes', $this->get_type(), $this->order ); ?>
				</div>
				<div class="customer-notes">
					<?php do_action( 'wpo_wcpdf_before_customer_notes', $this->get_type(), $this->order ); ?>
					<?php if ( $this->get_shipping_notes() ) : ?>
						<h3><?php _e( 'Customer Notes', 'woocommerce-pdf-invoices-packing-slips' ); ?></h3>
						<?php $this->shipping_notes(); ?>
					<?php endif; ?>
					<?php do_action( 'wpo_wcpdf_after_customer_notes', $this->get_type(), $this->order ); ?>
				</div>				
			</td>
			<td class="no-borders" colspan="2">
				<table class="totals">
					<tfoot>
						<?php foreach ( $this->get_woocommerce_totals() as $key => $total ) : ?>
							<tr class="<?php echo $key; ?>">
								<th class="description"><?php echo $total['label']; ?></th>
								<td class="price"><span class="totals-price"><?php echo $total['value']; ?></span></td>
							</tr>
						<?php endforeach; */ ?>
					</tfoot>
				</table>
			</td>
		</tr>
	</tfoot>
</table>-->

<div class="bottom-spacer"></div>

<?php do_action( 'wpo_wcpdf_after_order_details', $this->get_type(), $this->order ); ?>

<?php if ( $this->get_footer() ) : ?>
	<div id="footer">
		<!-- hook available: wpo_wcpdf_before_footer -->
		<?php $this->footer(); ?>
		<!-- hook available: wpo_wcpdf_after_footer -->
	</div><!-- #letter-footer -->
<?php endif; ?>

<?php do_action( 'wpo_wcpdf_after_document', $this->get_type(), $this->order ); ?>

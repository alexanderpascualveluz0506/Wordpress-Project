<!-- Tab links -->
<div class="tab">
	<?php foreach( $product_categories as $cat ) : ?>
		<button class="tablinks <?php if($product_categories[0]==$cat){echo 'active';} ?>" onclick="openCat(event, '<?php echo $cat->name;?>')"><?php echo $cat->name;?></button>
	<?php endforeach; ?>
</div>

<!-- Tab content -->
<?php foreach( $product_categories as $cat_content ) :?>
	<div id="<?php echo $cat_content->name;?>" class="tabcontent" 
	<?php if($product_categories[0]==$cat_content){echo 'style="display:block"';} ?>
	>

		<div class="flex mt-12">
			<?php
				$current = get_query_var('page') ? intval(get_query_var('page')) : 1;
				$args = [
					'orderby' => 'name',
					'paginate' => true,
					'limit' => 4,
					'status' => 'publish',
					'category' => $cat_content->name,
					'paged' => $current,
				];

       			 $products = wc_get_products($args);
			?>

				<?php foreach ($products->products as $product_key => $product): ?>
					<?php
					$image_id = $product->image_id;
					$image_url = wp_get_attachment_image_url($image_id, 'full');
					?>
					<div class="outline-black">
						<img src="<?php echo $image_url; ?>" alt="">
						<h3><?php echo $product->name; ?>
						</h3>
						<p><?php echo $product->description; ?>
						</p>
						<div><?php echo $product->description; ?>
						</div>
					</div>
				<?php endforeach; ?>
				
		</div>


	</div>
<?php endforeach; ?>

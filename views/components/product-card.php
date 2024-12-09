<div class="col-md-4 mb-4">
    <div class="card">
        
        <img src="<?php echo BASE_URL. $product["image_url"]; ?>" class="card-img-top mx-auto" alt="Product Image" style="height:300px;width:300px">
        <div class="card-body">
            <h5 class="card-title"><?php echo $product["product_name"]; ?></h5>
            <p class="card-text">Category: <?php echo $product["category_id"]; ?></p>
            <p class="card-text">Price: <?php echo number_format($product["unit_price"], 2); ?></p>
            <p class="card-text">Stock: <?php echo $product["stocks"]; ?></p>
            <p class="card-text">Total Price: <?php echo number_format($product["total_price"], 2); ?></p>
            <a href="<?php echo BASE_URL;?>views/admin/products/edit.php?id=<?php echo $product["id"]; ?>" class="btn btn-primary">Edit Product</a>
            
            <form action="<?php echo BASE_URL;?>app/product/delete_product.php" method = "POST" class="d-inline">
                <input type="hidden" name="id" value="<?php echo $product["id"]; ?>">
                <button type="submit" onclick="return confirm('Are you sure you want to delete this product?')"
                class="btn btn-danger">Delete Product</button>
            </form>

        </div>
    </div>
</div>
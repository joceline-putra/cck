
<main class="main">
    <div class="page-header">
        <div class="container d-flex flex-column align-items-center">
            <nav aria-label="breadcrumb" class="breadcrumb-nav">
                <div class="container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="demo4.html">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Wishlist
                        </li>
                    </ol>
                </div>
            </nav>

            <h1>Wishlist</h1>
        </div>
    </div>

    <div class="container">
        <div class="wishlist-title">
            <h2 class="p-2">My wishlist on Porto Shop 4</h2>
        </div>
        <div class="wishlist-table-container">
            <table class="table table-wishlist mb-0">
                <thead>
                    <tr>
                        <th class="thumbnail-col"></th>
                        <th class="product-col">Product</th>
                        <th class="price-col">Price</th>
                        <th class="status-col">Stock Status</th>
                        <th class="action-col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="product-row">
                        <td>
                            <figure class="product-image-container">
                                <a href="product.html" class="product-image">
                                    <img src="<?php echo $asset;?>assets/images/products/product-4.jpg" alt="product">
                                </a>

                                <a href="#" class="btn-remove icon-cancel" title="Remove Product"></a>
                            </figure>
                        </td>
                        <td>
                            <h5 class="product-title">
                                <a href="product.html">Men Watch</a>
                            </h5>
                        </td>
                        <td class="price-box">$17.90</td>
                        <td>
                            <span class="stock-status">In stock</span>
                        </td>
                        <td class="action">
                            <a href="ajax/product-quick-view.html" class="btn btn-quickview mt-1 mt-md-0"
                                title="Quick View">Quick
                                View</a>
                            <button class="btn btn-dark btn-add-cart product-type-simple btn-shop">
                                ADD TO CART
                            </button>
                        </td>
                    </tr>

                    <tr class="product-row">
                        <td>
                            <figure class="product-image-container">
                                <a href="product.html" class="product-image">
                                    <img src="<?php echo $asset; ?>assets/images/products/product-5.jpg" alt="product">
                                </a>

                                <a href="#" class="btn-remove icon-cancel" title="Remove Product"></a>
                            </figure>
                        </td>
                        <td>
                            <h5 class="product-title">
                                <a href="product.html">Men Cap</a>
                            </h5>
                        </td>
                        <td class="price-box">$17.90</td>
                        <td>
                            <span class="stock-status">In stock</span>
                        </td>
                        <td class="action">
                            <a href="ajax/product-quick-view.html" class="btn btn-quickview mt-1 mt-md-0"
                                title="Quick View">Quick
                                View</a>
                            <a href="product.html" class="btn btn-dark btn-add-cart btn-shop">
                                SELECT OPTION
                            </a>
                        </td>
                    </tr>

                    <tr class="product-row">
                        <td>
                            <figure class="product-image-container">
                                <a href="product.html" class="product-image">
                                    <img src="<?php echo $asset; ?>assets/images/products/product-6.jpg" alt="product">
                                </a>

                                <a href="#" class="btn-remove icon-cancel" title="Remove Product"></a>
                            </figure>
                        </td>
                        <td>
                            <h5 class="product-title">
                                <a href="product.html">Men Black Gentle Belt</a>
                            </h5>
                        </td>
                        <td class="price-box">$17.90</td>
                        <td>
                            <span class="stock-status">In stock</span>
                        </td>
                        <td class="action">
                            <a href="ajax/product-quick-view.html" class="btn btn-quickview mt-1 mt-md-0"
                                title="Quick View">Quick
                                View</a>
                            <a href="product.html" class="btn btn-dark btn-add-cart btn-shop">
                                SELECT OPTION
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div><!-- End .cart-table-container -->
    </div><!-- End .container -->
</main>

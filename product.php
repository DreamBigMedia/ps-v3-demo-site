<!DOCTYPE html>
<html>
  <head>
    <?php require("header-titleless.php");?>
    <title>ShopFoo</title>
  </head>
  <body>
    <?php require("navbar.php");?>
    <div class="container">
      <div class="row">
        <div class="span2 sidebar">
	  <h3>Categories</h3>
	  <hr>
	  <ul>
	  <?php
	    require("include-before-call.php");
	      $api = new PsApiCall($api_key, $catalog_key, true);
	      $api->get('products');
	    require("include-after-call.php");
	    $p = $api->getProducts();
            $p = $p[0];
	    foreach($api->getCategories() as $category) { ?>
	      <li>
		<a href="search.php?psapi_keyword=&psapi_category=<?= $category->getId() ?>">
		  <?= $category->getName() ?>
		</a>
	      </li>
	    <?php } ?>
	  </ul>
        </div>
        <div class="span10">
	  <h2><?= $p->getName() ?></h2>
	  <hr>
	  <div class="row">
	    <div class="span10">
	      <a href="<?= $p->largestImageUrl() ?>">
		<img class="product-detail-large-image" src="<?= $p->largestImageUrl() ?>">
	      </a>
	      <span class="product-detail-description">
		<?= $p->getDescription() ?>
	      </span>
	      <br><br>
	      <span class="brand-label">Brand: </span><span class="brand-value"><?= $p->getBrand()->getName() ?></span>
	    </div>
	  </div>
	  <div class="row">
	    <div class="span12">
	      <hr>
	      <h2>
		Offers
	      </h2>
	    </div>
	  </div>
	  <div class="row">
	    <div class="span2">
	      <h3>Store</h3>
	    </div>
	    <div class="span2">
	      <h3>Price</h3>
	    </div>
	    <div class="span2">
	      <h3>Condition</h3>
	    </div>
	  </div>
	  <table class="table-hover offers-table">
	    <?php
	      foreach($p->getOffers() as $offer) {
		renderOffer($offer);
	      }
	    ?>
	  </table>
	  <a type="button" class="btn inspect-button" href="#inspect_modal" data-toggle="modal">Inspect</a>
	</div>
      </div>
    </div>
    <?php require('bottom-js-includes.php');?>
  </body>
</html>

<!DOCTYPE html>
<html>
  <head>
    <?php require("header-titleless.php"); ?>
    <title>Stores</title>
  </head>
  <body>
    <?php
      require("navbar.php");
      require("include-before-call.php");
	$api = new PsApiCall($api_key, $catalog_key, true);
	$api->call('merchants');
      require("include-after-call.php");
    ?>
    <div class="container">
      <div class="row">
        <div class="span3 sidebar">
	  <span class="sidebar-heading">Select store category:</span>
	  <ul class="sidebar-option-ul">
	    <?php
	      foreach(sortByName($api->getCategories()) as $category) {
		$checked = ($api->hasParameter('category') and ($category->getId() == $api->getParameterValue('category')));
		?>
		<li>
		  <a href="<?php
		  if ($checked) {
		    print($api->getQueryString(array('category' => '', 'page' => '1')));
		  } else {
		    print($api->getQueryString(array('category' => $category->getId(), 'page' => '1', 'alpha' => '')));
		  } ?>
		    ">	    
		    <?php if ($checked) { print("<i class='sidebar-close-icon icon-remove'></i>"); } ?>
		    <small <?php if ($checked) { print('style="font-weight:bold;"'); } ?>>
		      <?= $category->getName() . getCountHtml($category->getCount()) ?>
		    </small>
		  </a>
		</li>
		<?php
	      }
	    ?>
	  </ul>
        </div>
        <div class="span9">
	  <h2>
	    <?php
	      if ($api->hasParameter('category')) {
		if ($api->getParameterValue('category') != '') {
		  if ($api->getCategory($api->getParameterValue('category'))) {
		    print($api->getCategory($api->getParameterValue('category'))->getName() . ' ');
		  }
		}
	      }
	    ?>
	    Stores
	  </h2>
	  <div>
	    <form name="alphachoose" method="get" action="merchants.php" class="merchant-alpha-select">
	      <?php generateHiddenParameters($api, array('alpha', 'page')); ?>
	      <span style="padding-bottom:10px;" class="help-inline">Starting with</span>
	      <select onchange="this.form.submit()" name="psapi_alpha" class="span1" id="alpha">
		<option id="store-alpha-select-" value=''>Any</option>
		<option id="store-alpha-select-0" value="0">#</option>
		<?php
		  foreach(str_split('ABCDEFGHIJKLMNOPQRSTUVWXYZ') as $letter) { ?>
		    <option id="store-alpha-select-<?= ord($letter)-64 ?>" value="<?= (ord($letter)-64) ?>">
		      <?= $letter ?>
		    </option>
		    <?php
		  }
		?>
	      </select>
	    </form>
	    <?php
	      if($api->hasParameter("alpha")) { ?>
		<script language="javascript">
		  document.getElementById("store-alpha-select-<?= $api->getParameterValue("alpha") ?>").selected = "true";
		</script>
		<?php
	      }
	    ?>
	    <?php generateBootstrapPagination($api, 8, false) ?>
	  </div>
	  <br />
	  <table class="table-hover">
	    <tr>
	      <td class="span2">
	      </td>
	      <td class="span4 merchant-table-header">
		Store
	      </td>
	      <td align="center" class="span2 merchant-table-header">
		Products
	      </td>
	      <td align="center" class="span2 merchant-table-header">
		Coupons
	      </td>
	    </tr>	  
	    <?php
	      foreach ($api->getMerchants() as $merchant) {
		if (($merchant->getProductCount() != 0) or ($merchant->getDealCount() != 0)) {
		  renderMerchant($merchant);
		}
	      }
	    ?>
	  </table>
	  <hr />
	  <?php generateBootstrapPagination($api) ?>
	  <?php require('footer.php'); ?>
	</div>
      </div>
    </div>
    <?php require('bottom-js-includes.php');?>
  </body>
</html>

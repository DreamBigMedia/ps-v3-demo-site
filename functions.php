<?php

  function renderOffer($offer) { ?>
    <tr>
      <td class="span2">
        <?php
          if ($offer->getMerchant()->getLogoUrl()) { ?>
            <img class="merchant-small-image img-rounded" src="<?=$offer->getMerchant()->getLogoUrl() ?>">
            <?php
          } else { ?>
            <?= $offer->getMerchant()->getName() ?>
            <?php
          }
        ?>
      </td>
      <td align="center" class="span2">
        <span class="offer-price-merchant">$<?= money_format('%i', $offer->getPriceMerchant()) ?></span>
      </td>
      <td align="center" class="span2">
        <span class="offer-condition"><?= ucfirst($offer->getCondition()) ?></span>
      </td>
      <td class="span2">
        <a href="<?= $offer->getUrl() ?>" rel="nofollow" class="btn offer-store-button btn-warning">Go to Store</a>
      </td>
    </tr>
  <?php }

  function renderMerchant($merchant) { ?>
    <tr>
      <td class="span2">
        <img class="img-rounded merchant-small-image" src="<?=$merchant->getLogoUrl()?>">
      </td>
      <td class="span4">
        <a href="search.php?psapi_merchant=<?= $merchant->getId() ?>">
          <?=$merchant->getName()?>
        </a>
      </td>
      <td class="span2" align="center">
        <?php
          $count = $merchant->getProductCount();
          if ($count > 0) { ?>
            <a href="search.php?psapi_merchant=<?= $merchant->getId() ?>">
              <?=$merchant->getProductCount()?>
            </a>
            <?php
          } else {
            print($merchant->getProductCount());
          }
        ?>
      </td>
      <td class="span2" align="center">
        <?php
          $count = $merchant->getDealCount();
          if ($count > 0) { ?>
            <a href="coupons.php?psapi_merchant=<?= $merchant->getId() ?>">
              <?=$merchant->getDealCount()?>
            </a>
            <?php
          } else {
            print($merchant->getDealCount());
          }
        ?>
      </td>
    </tr>
  <?php }

  function renderProduct($product) {
    $description_cutoff = 250; ?>
    <div class="row">
      <div class="span2">
        <a href="product.php?psapi_product=<?= $product->getId()?>">
          <img class="img-hover-zoom search-product-image" src="<?= $product->largestImageUrl() ?>">
        </a>
      </div>
      <div class="span5">
        <a href="product.php?psapi_product=<?= $product->getId() ?>"><?= $product->getName() ?></a>
        <br /><br />
        <div class="product-description more-less">
        <?php
          if (strlen($product->getDescription()) > $description_cutoff) { ?>
            <div class="less">
              <?= substr(htmlentities($product->getDescription()), 0, $description_cutoff) ?>...
              <a href="#" class="read-more">more</a>
            </div>
            <div class="more" style="display:none;">
              <?= $product->getDescription() ?>
              <a href="#" class="read-less">less</a>
            </div>
          <?php } else { ?>
            <?= htmlentities($product->getDescription()) ?>
          <?php } ?>
      </div>
      </div>
      <div align="center" class="span2">
        <?php if ($product->getPriceMin() == $product->getPriceMax()) { ?>
          <span class="dollar-sign">$</span><span class="product-price"><?= money_format('%i', $product->getPriceMin()) ?></span>
        <?php } else { ?>
          <span class="dollar-sign">$</span><span class="product-price"><?= money_format('%i', $product->getPriceMin()) ?></span><i> &#8213; </i>
          <span class="dollar-sign">$</span><span class="product-price"><?= money_format('%i', $product->getPriceMax()) ?></span>
        <?php } ?>
        <br><br>
        <a class="btn compare-offers-btn btn-warning" href="product.php?psapi_product=<?= $product->getId() ?>">
          <?php if (((int) $product->getOfferCount()) === 1) { print('View Offer'); } else { print('Compare Offers'); } ?>
        </a>
        <br />
        <small class="offers-available">
          <?= $product->getOfferCount() ?>
          <?php
            if (((int) $product->getOfferCount()) === 1) {
              print('offer');
            } else {
              print('offers');
            }
          ?>
          available
        </small>
      </div>
    </div>
    <hr />
  <?php }
  
  function renderDealSidebar($deal) { ?>
    <div class="well deal-sidebar">
      <a rel="nofollow" href="<?= $deal->getUrl() ?>">
        <div class="deal-sidebar-buttons">
          <img class="img-rounded deal-sidebar-img" src="<?= $deal->getMerchant()->getLogoUrl() ?>" />
          <?php
            if (($deal->getCode() != '') and !in_array(strtolower($deal->getCode()),
              array('none required', 'none', 'no code required', 'n/a', 'no code needed', 'no coupon code')) and
              (strpos(strtolower($deal->getCode()), 'required') === FALSE)) {
              generateCouponModal($deal); ?>
              <a class="btn btn-warning deal-sidebar-redeem" href="#deal-modal-<?= $deal->getId() ?>" data-toggle="modal">
                View Code
              </a>
              <?php
            } else { ?>
              <a class="btn btn-warning deal-sidebar-redeem" rel="nofollow" href="<?= $deal->getUrl() ?>">Redeem</a>
              <?php
            }
          ?>
        </div>
        <div class="deal-sidebar-name"><?= $deal->getName() ?></div>
      </a>
      <div class="deal-sidebar-expires">Expires <?= $deal->getEndOn() ?></div>
    </div>
    <?php
  }
  
  function renderDeal($deal) { ?>
    <div class="row">
      <div class="span2">
        <a rel="nofollow" href="<?= $deal->getUrl() ?>">
          <?php
            if ($deal->getMerchant() and $deal->getMerchant()->getLogoUrl()) { ?>
              <img class="img-rounded merchant-small-image" src="<?= $deal->getMerchant()->getLogoUrl() ?>">
              <?php
            } else if ($deal->getMerchant()) { ?>
              <span class="deal-merchant-name"><?= $deal->getMerchant()->getName() ?></span>
              <?php
            }
          ?>
        </a>
      </div>
      <div class="span3 deal-name">
        <a rel="nofollow" href="<?= $deal->getUrl() ?>"><?= $deal->getName() ?></a>
      </div>
      <div class="span2">
        <small>
          <?php
            if ($deal->getStartOn() != '') { ?>
              <span class="deal-start-label">Valid from: </span><span class="deal-start-value"><?= $deal->getStartOn() ?></span><br />
              <?php
            }
            if (($deal->getEndOn() != '') and ($deal->getEndOn() != '01/01/2017')) { ?>
              <span class="deal-end-label">Good through: </span><span class="deal-end-value"><?= $deal->getEndOn() ?></span><br />
              <?php
            }
          ?>
        </small>
      </div>
      <div class="span2">
        <?php
          if (($deal->getCode() != '') and !in_array(strtolower($deal->getCode()),
            array('none', 'no code required', 'n/a', 'no code needed', 'no coupon code')) and
            (strpos(strtolower($deal->getCode()), 'required') === FALSE)) {
            generateCouponModal($deal); ?>
            <a class="btn btn-warning" href="#deal-modal-<?= $deal->getId() ?>" data-toggle="modal">
              View Code
            </a>
            <?php
          } else { ?>
            <a class="btn btn-warning" rel="nofollow" href="<?= $deal->getUrl() ?>">Redeem</a>
            <?php
          }
        ?>
      </div>
    </div>
    <hr />
  <?php
}
  
  function generateBootstrapPagination($api, $num_cells=8, $center=true) {
    $current = 1;
    $total = (int) $api->getResultsCount();
    $per_page = 20;
    if ($api->hasParameter('results_per_page')) { $per_page = (int) $api->getParameterValue('results_per_page'); }
    if ($api->hasParameter('page')) { $current = (int) $api->getParameterValue('page'); }
    $pages = (int) ($total / $per_page);
    if ($total % $per_page != 0.0) {
      $pages += 1;
    }
    if ($pages < 2) { ?>
      <div class="pagination generated-pagination <?php if ($center) { print('pagination-centered'); } ?>">
        <ul>
          <li class="active">
            <a href="#">
              Page 1 of 1
            </a>
          </li>
        </ul>
      </div>
      <?php
      return;
    }
    $half = (int) ($num_cells / 2);
    $min = 0;
    if ($current < ($half + 1)) {
      $min = 1;
    } else {
      $min = $current - $half;
    }?>
    <div class="pagination generated-pagination <?php if ($center) { print('pagination-centered'); } ?>">
      <ul>
        <li <?php if ($current === 1) { print('class="active"'); } ?>><a href="<?= $api->prevPage() ?>">&laquo; Prev</a></li>
        <?php
          for ($i=$min; $i < $min + $num_cells; $i += 1) {
            if ($i <= $pages) { ?>
              <li <?php if ($i === $current) { print('class="active"'); } ?>>
                <a href="<?= $api->paginate($i) ?>">
                  <?= $i ?>
                </a>
              </li>
              <?php
            }
          }
        ?>
        <li <?php if ($current === $pages) { print('class="active"'); } ?>><a href="<?= $api->nextPage() ?>">Next &raquo;</a></li>
      </ul>
    </div><?php
  }
  
  function generateHiddenParameters($api, $omit=array()) {
    $omit[] = 'catalog';
    $omit[] = 'account';
    foreach ($api->getOptions() as $option=>$value) {
      if (! in_array($option, $omit)) { ?>
        <input type="hidden" name="<?= $api->getUrlPrefix() . $option ?>" value="<?= $value ?>">
        <?php
      }
    }
  }
  
  function getCategoryIcon($s) {
    switch ($s) {
      case "32194": // Arts & Crafts
        return "icon-pencil";
      case "2000": // Automotive Parts & Vehicles
        return "icon-road";
      case "15000": // Baby & Family
        return "icon-home";
      case "3000": // Clothing & Accessories
        return "icon-briefcase";
      case "5000": // Computers & Software
        return "icon-off";
      case "7000": // Electronics
        return "icon-camera";
      case "10000": // Events & Tickets
        return "icon-tags";
      case "12000": // Food, Flowers & Gifts
        return "icon-gift";
      case "13000": // Health & Beauty
        return "icon-eye-open";
      case "16000": // Home & Garden
        return "icon-home";
      case "9000": // Mature & Adult
        return "icon-heart";
      case "21000": // Media
        return "icon-film";
      case "22000": // Musical Instruments
        return "icon-music";
      case "24000": // Office & Professional Supplies
        return "icon-envelope";
      case "23000": // Pets & Animal Supplies
        return "icon-leaf";
      case "25000": // Shoes & Accessories
        return "icon-shopping-cart";
      case "32346": // Specialty & Novelty
        return "icon-star";
      case "27000": // Sports & Outdoor Activities
        return "icon-globe";
      case "31000": // Toys, Games & Hobbies
        return "icon-bell";
      case "32345": // Travel
        return "icon-plane";
      case "8400": // Video Games, Consoles & Accessories
        return "icon-hdd";
      case "9100": // Weapons
        return "icon-screenshot";
      default:
        return "icon-certificate";
    }
  }
  
  function getCountHtml($num) {
     if ($num == '') {
      return '';
     } else {
      if ($num > 0) {
        return ' <span class="selection-count">(' . $num . ')</span>';
      } else {
        return '';
      }
    }
  }
  
  function cmpObjByName($a, $b) { 
    if ($a->getName() ==  $b->getName()) {
      return 0;
    } else if ($a->getName() == 'All') {
      return -1;
    } else if ($b->getName() == 'All') {
      return 1;
    } else {
      return ($a->getName() < $b->getName()) ? -1 : 1;
    }
  }

  function sortByName($arr) {
    $tmp = $arr;
    usort($tmp, "cmpObjByName");
    return $tmp;
  }
  
  function generateCouponModal($deal) { ?>
    <div class="modal fade span3 coupon-modal" id="deal-modal-<?= $deal->getId() ?>">
      <div class="modal-header">
	<a class="close" data-dismiss="modal">&times;</a>
	<h3>Coupon</h3>
      </div>
      <div class="modal-body well">
        <a rel="nofollow" href="<?= $deal->getUrl() ?>">
          <div class="deal-sidebar-buttons">
            <?php
              if ($deal->getMerchant()) { ?>
                <img class="img-rounded deal-sidebar-img" src="<?= $deal->getMerchant()->getLogoUrl() ?>" />
                <?php
              } else {
                print("Unknown");
              }
            ?>
            <a rel="nofollow" class="btn btn-warning deal-sidebar-redeem" href="<?= $deal->getUrl() ?>">Redeem</a>
          </div>
          <div class="deal-sidebar-name"><?= $deal->getName() ?></div>
        </a>
        <div class="deal-sidebar-expires">Expires <?= $deal->getEndOn() ?></div>
        <div>
          <?php
            if (($deal->getCode() != '') and !in_array(strtolower($deal->getCode()),
                array('none required', 'none', 'no code required', 'n/a', 'no code needed', 'no coupon code'))) {
              if (strpos(strtolower($deal->getCode()), 'required') === FALSE) { ?>
                <span class="deal-sidebar-code-label">Coupon code: </span><span class="deal-sidebar-code-value"><?= $deal->getCode() ?></span>
                <?php
              }
            }
          ?>
        </div>
      </div>
    </div>
    <?php
  }
?>
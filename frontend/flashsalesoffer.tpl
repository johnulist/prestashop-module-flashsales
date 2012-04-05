{if isset($flashsalesoffer)}
{assign var='price' value={convertPrice price=$flashsalesoffer->prices['min_price']}}
{assign var='price_reduce' value={convertPrice price=$flashsalesoffer->prices['min_price_reduce']}}
{assign var='priceSplit' value=','|explode:$price}
<script type="text/javascript">
// <![CDATA[

// PrestaShop internal settings
var currencySign 		= '{$currencySign|html_entity_decode:2:"UTF-8"}';
var currencyRate 		= '{$currencyRate|floatval}';
var currencyFormat 	= '{$currencyFormat|intval}';
var currencyBlank		= '{$currencyBlank|intval}';
var displayPrice 		= {$priceDisplay};
var ecotaxTax_rate 	= {$ecotaxTax_rate};
//]]>
</script>
<div id="highlight">
	{include file="$tpl_dir./breadcrumb-hightlight.tpl"}
	<i class="sprite border-highlight"></i>
	<h1 class="highlight-title-product">{$flashsalesoffer->name|escape:'htmlall':'UTF-8'}</h1>
	<div id="info-product">
	<p>{l s='à partir de'}</p>
	<div class="price-container clearfix">
		<p class="price">{$priceSplit[0]}<span class="cts-minimify">,{$priceSplit[1]}</span></p>
		<p class="price-red">{$priceSplit[0]}<span class="cts-minimify">,{$priceSplit[1]}</span></p>
	</div>
	{if $flashsalesoffer->prices['reduction']}
	<span class="original-price">{$price_reduce}</span>
	{/if}
	{if $flashsalesoffer->prices['reduction'] AND $flashsalesoffer->prices['reduction'].reduction_type == 'percentage'}
		<span class="remise">-{$flashsalesoffer->prices['reduction'].reduction*100}%</span>
	{/if}
</div>
<div id="product-highlight">
	<div id="container-product-display-product-page-view">
		{foreach $flashsalesoffer->products key=key_product item=fproduct}
		{assign var='product' value=$fproduct['product']}
		{assign var='images' value=$fproduct['images']}
		{assign var='groups' value=$fproduct['groups']}
		{assign var='combinations' value=$fproduct['combinations']}
		{assign var='colors' value=$fproduct['colors']}
		<div class="sub-product clearfix">
		<script type="text/javascript">
		// <![CDATA[

		// Parameters
		var taxRate_{$product->id} = {$taxes_rate[$product->id]};
		var noTaxForThisProduct_{$product->id} = {if $no_taxes[$product->id] eq 1}true{else}false{/if};
		var productPriceTaxExcluded_{$product->id} = {$product->getPriceWithoutReduct(true)|default:'null'} - {$product->ecotax};
		var productShowPrice_{$product->id} = '{if !$PS_CATALOG_MODE}{$product->show_price}{else}0{/if}';
		var specific_price_{$product->id} = {if $product->specificPrice AND $product->specificPrice.price}{$product->specificPrice.price}{else}0{/if};
		var specific_currency_{$product->id} = {if $product->specificPrice AND $product->specificPrice.id_currency}true{else}false{/if};
		var reduction_percent_{$product->id} = {if $product->specificPrice AND $product->specificPrice.reduction AND $product->specificPrice.reduction_type == 'percentage'}{$product->specificPrice.reduction*100}{else}0{/if};
		var reduction_price_{$product->id} = {if $product->specificPrice AND $product->specificPrice.reduction AND $product->specificPrice.reduction_type == 'amount'}{$product->specificPrice.reduction}{else}0{/if};
		var group_reduction_{$product->id} = '{$groups_reduction[$product->id]}';
		var default_eco_tax_{$product->id} = {$product->ecotax};
		Prestashop.flashsales.frontend.combinations[{$product->id}] = new Array();
		

		{if isset($groups) && $groups}
			// Combinations
			{foreach from=$combinations key=idCombination item=combination}
				Prestashop.flashsales.frontend.addCombination({$product->id|intval}, {$idCombination|intval}, new Array({$combination.list}), {$combination.quantity}, {$combination.price}, {$combination.ecotax}, {$combination.id_image}, '{$combination.reference|addslashes}', {$combination.unit_impact}, {$combination.minimal_quantity});
			{/foreach}

			// Colors
			{if $colors|@count > 0}
			{if $product->id_color_default}
			var id_color_default_{$product->id} = {$product->id_color_default|intval};
			{/if}
			{/if}
		{/if}

		//]]>
		</script>
			<div class="left-side">
				<h2>{$product->name|escape:'htmlall':'UTF-8'}</h2>
				<h3>{$product->description|strip_tags}</h3>
				<div class="thumbnail-sub-product">
					<ul>
					{*if $flashsalesoffer->video && $flashsalesoffer->video_forward}
					<li class="video"><a href="http://youtu.be/{$flashsalesoffer->video}" target="_blank"><i class="sprite video_ico">{l s='Vidéo du produit'}</i></a></li>
					{/if*}
						{foreach $images item=image}
						{assign var=imageIds value="`$product->id`-`$image.id_image`"}
						<li id="thumbnail_{$image.id_image}">
							<a rel="group-picture-product_{$key_product}" href="{$link->getImageLink($product->link_rewrite, $imageIds, 'pictofferfancy')}" title="{$image.legend|htmlspecialchars}">
								<img id="thumb_{$image.id_image}" src="{$link->getImageLink($product->link_rewrite, $imageIds, 'pictofferthumbs')}" alt="{$image.legend|htmlspecialchars}">
								<div class="picture-other-product-hover">
									<i class="sprite take-a-look-product"></i>
								</div>
							</a>
						</li>
						{/foreach}
						{*if $flashsalesoffer->video && $flashsalesoffer->video_forward eq 0}
						<li class="video">
							<a href="http://youtu.be/{$flashsalesoffer->video}" target="_blank">
								<i class="sprite video_ico">{l s='Vidéo du produit'}</i>
								<span>{l s='Vidéo'}</span>
							</a>
							</li>
						{/if*}
					</ul>
				</div>
				{assign var='productPrice' value=$product->getPrice(true, $smarty.const.NULL, 2)}
				{assign var='productPriceWithoutRedution' value=$product->getPriceWithoutReduct(false, $smarty.const.NULL)}
				{assign var='price' value={convertPrice price=$productPrice}}
				{assign var='priceSplit' value=','|explode:$price}
				<p class="price">{$priceSplit[0]},<span class="cts-product">{$priceSplit[1]}</span></p>
				<p class="hidden">
					<input type="hidden" name="token" value="{$static_token}" />
					<input type="hidden" name="id_product" value="{$product->id|intval}" id="product_id" />
					<input type="hidden" name="add" value="1" />
					<input type="hidden" name="id_product_attribute" id="idCombination" value="" />
				</p>
				<div class="groups clearfix">
					{foreach from=$groups key=id_attribute_group item=group}
					{if $group.attributes|@count}
					<p>
						<label for="group_{$id_attribute_group|intval}">{$group.name|escape:'htmlall':'UTF-8'} :</label>
						{assign var="groupName" value="group_$id_attribute_group"}
						<input type="hidden" value="{$product->id}" />
						<select name="{$groupName}" id="group_{$id_attribute_group|intval}" onchange="{if $colors|@count > 0}$('#wrapResetImages').show('slow');{/if};">
							{foreach from=$group.attributes key=id_attribute item=group_attribute}
								<option value="{$id_attribute|intval}"{if (isset($smarty.get.$groupName) && $smarty.get.$groupName|intval == $id_attribute) || $group.default == $id_attribute} selected="selected"{/if} title="{$group_attribute|escape:'htmlall':'UTF-8'}">{$group_attribute|escape:'htmlall':'UTF-8'}</option>
							{/foreach}
						</select>
					</p>
					{/if}
					{/foreach}
				</div>
			</div>
		
			<div class="right-side">
				<label for="qty">{l s='Quantité :'}</label>
				<input type="text" name="qty" id="quantity_wanted" value="1">
				<a href="" title="" class="sprite acheter"></a>
				<a href="" title="" class="offer-to-friends"><i class="sprite cadeau"></i>{l s='Offrez-le à un ami'}</a>
				<ul class="addthis_toolbox addthis_default_style share" addthis:ui_language="fr" addthis:url="{$flashsalesoffer->offerLink}" addthis:title="{l s='Découvrez l\'offre du jour sur #Only24h :'} {$flashsalesoffer->name|escape:'htmlall':'UTF-8'}" addthis:description="{$flashsalesoffer->description_short|escape:'htmlall':'UTF-8'}">
					<li><a class="addthis_button_facebook at300b"></a></li>
					<li><a class="addthis_button_twitter"><i class="sprite tw"></i></a></li>
					<li><a class="addthis_button_email" addthis:ui_language="fr"></a></li>
				</ul>
			</div>
		</div>
		{/foreach}
	</div><!-- End#product-highlight -->
</div>
{if $flashsalesoffer_others|@count gt 1}
<div id="other-product">
	<p class="other_offer_title">{l s='Les autres offres'}</p>
	<ul class="clearfix">
		{foreach $flashsalesoffer_others key=offer_key item=offer}
			{assign var="image" value=$offer->images[0]}
			{assign var='price' value={convertPrice price=$offer->prices['min_price']}}
			{assign var='priceSplit' value=','|explode:$price}
			{assign var='price_reduce' value={convertPrice price=$offer->prices['min_price_reduce']}}
			<li>
				<a href="" title="">
					<div class="visuel-other-product"><img src="{$link->getImageLink('offer', $image, 'pictotheroffer')}" alt=""></div>
					<div id="visuel-{$offer_key}" class="visuel-other-product-hover">
						<div>
							<i class="sprite take-a-look"></i>
							<p>{l s='Voir l\'offre'}</p>
						</div>
					</div>
					<div>
						<p class="product-name">{$offer->name|escape:'htmlall':'UTF-8'}</p>
						{if $offer->prices['reduction'] neq 0}
						<span class="original-price">{$price_reduce}</span>
						<span class="remise">-{$offer->prices['reduction'].reduction*100}% !</span>
						{/if}
						<p class="price-fix-ie">{$priceSplit[0]}<span class="cts-minimify">,{$priceSplit[1]}</span></p>
					</div>
				</a>
			</li>
		{/foreach}
	</ul>
</div><!-- End#other-product -->
{/if}
{else}
{include file="$tpl_dir./errors.tpl"}
{/if}
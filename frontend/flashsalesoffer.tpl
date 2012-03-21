{assign var='price' value={convertPrice price=$flashsalesoffer->prices['min_price']}}
{assign var='price_reduce' value={convertPrice price=$flashsalesoffer->prices['min_price_reduce']}}
{assign var='priceSplit' value=','|explode:$price}
<div id="highlight">
	<div id="breadcrumb" class="clearfix">
		<i class="sprite home"></i>
		<a href="" title="">{l s='Retour à l\'accueil'}</a>
		<i class="sprite how-work-i"></i>
		<a href="" title="">{l s='Comment ça marche ?'}</a>
	</div>
	<i class="sprite border-highlight"></i>
	<h2 class="highlight-title-product">{$flashsalesoffer->name|escape:'htmlall':'UTF-8'}</h2>
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

		<div class="sub-product clearfix">
			<div class="left-side">
				<h3>{$product->name|escape:'htmlall':'UTF-8'}</h3>
				<p>{$product->description_short}</p>
				<div class="thumbnail-sub-product">
					<ul>
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
					</ul>
				</div>
				{assign var='productPrice' value=$product->getPrice(true, $smarty.const.NULL, 2)}
				{assign var='productPriceWithoutRedution' value=$product->getPriceWithoutReduct(false, $smarty.const.NULL)}
				{assign var='price' value={convertPrice price=$productPrice}}
				{assign var='priceSplit' value=','|explode:$price}
				<p class="price">{$priceSplit[0]},<span class="cts-product">{$priceSplit[1]}</span></p>
			</div>
		
			<div class="right-side">
				<label for="qty">{l s='Quantité :'}</label>
				<input type="text" name="qty" id="qty">
				{foreach from=$groups key=id_attribute_group item=group}
				{if $group.attributes|@count}
				<p>
					<label for="group_{$id_attribute_group|intval}">{$group.name|escape:'htmlall':'UTF-8'} :</label>
					{assign var="groupName" value="group_$id_attribute_group"}
					<select name="{$groupName}" id="group_{$id_attribute_group|intval}" onchange="javascript:findCombination();{if $colors|@count > 0}$('#wrapResetImages').show('slow');{/if};">
						{foreach from=$group.attributes key=id_attribute item=group_attribute}
							<option value="{$id_attribute|intval}"{if (isset($smarty.get.$groupName) && $smarty.get.$groupName|intval == $id_attribute) || $group.default == $id_attribute} selected="selected"{/if} title="{$group_attribute|escape:'htmlall':'UTF-8'}">{$group_attribute|escape:'htmlall':'UTF-8'}</option>
						{/foreach}
					</select>
				</p>
				{/if}
				{/foreach}
				<a href="" title="" class="sprite acheter"></a>
				<a href="" title="" class="offer-to-friends"><i class="sprite cadeau"></i>Offrez-le à un ami</a>
				<ul class="share">
					<li><a href="" title=""><i class="sprite fb"></i></a></li>
					<li><a href="" title=""><i class="sprite tw"></i></a></li>
					<li><a href="" title=""><i class="sprite mail"></i></a></li>
				</ul>
			</div>
		</div>
		{/foreach}
</div><!-- End#product-highlight -->

<div id="other-product" class="clearfix">
	<p>Les autres offres</p>
	<ul>
		<li>
			<a href="" title="">
				<div class="visuel-other-product"><img src="tmp/produit-other.jpg" alt=""></div>
				<div class="visuel-other-product-hover">
					<div>
						<i class="sprite take-a-look"></i>
						<p>Voir le produit</p>
					</div>
				</div>
				<div>
					<p class="product-name">Lissage japonais</p>
					<span class="original-price">109,29€</span>
					<span class="remise">-40% !</span>
					<p>49<span class="cts-minimify">,35€</span></p>
				</div>
			</a>
		</li>
		<li>
			<a href="" title="">
				<div class="visuel-other-product"><img src="tmp/produit-other.jpg" alt=""></div>
				<div class="visuel-other-product-hover">
					<div>
						<i class="sprite take-a-look"></i>
						<p>Voir le produit</p>
					</div>
				</div>
				<div>
					<p class="product-name">Lissage japonais</p>
					<span class="original-price">109,29€</span>
					<span class="remise">-40% !</span>
					<p>49<span class="cts-minimify">,35€</span></p>
				</div>
			</a>
		</li>
		<li>
			<a href="" title="">
				<div class="visuel-other-product"><img src="tmp/produit-other.jpg" alt=""></div>
				<div class="visuel-other-product-hover">
					<div>
						<i class="sprite take-a-look"></i>
						<p>Voir le produit</p>
					</div>
				</div>
				<div>
					<p class="product-name">Lissage japonais</p>
					<span class="original-price">109,29€</span>
					<span class="remise">-40% !</span>
					<p>49<span class="cts-minimify">,35€</span></p>
				</div>
			</a>
		</li>
	</ul>
</div><!-- End#other-product -->
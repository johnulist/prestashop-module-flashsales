{assign var='module' value=$flashsales}
<!-- MODULE {$module.module_name} -->
	<div id="product-highlight">
		<a href="" title="" class="container-back control"><i class="sprite back"></i></a>
		<div id="container-product-display">
			<div id="container-product-display-inner" class="clearfix">
				{foreach $module.offers key=offer_key item=offer}
				<div class="product-display first-product">
					<div id="left-side">
						{if $offer->video}
						{literal}
						<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/swfobject/2.2/swfobject.js"></script>		
						<script type="text/javascript">
							var params = { allowScriptAccess: "always" };
							var atts = { id: "ytapiplayer" };
							swfobject.embedSWF("http://www.youtube.com/v/{/literal}{$offer->video}{literal}?enablejsapi=1&playerapiid=ytapiplayer&version=3&showinfo=0&theme=light&wmode=opaque&fs=1", "ytapiplayer", "{/literal}{$pictofferSize.width}{literal}", "{/literal}{$pictofferSize.height}{literal}", "8", null, null, params, atts);
							</script>
						{/literal}
						{/if}
						<div class="picture">
							{if $offer->video && $offer->video_forward}
							<a href="#" class="video_iframe to_defile">
								<div id="ytapiplayer">
									{l s='Vous avez besoin de Flash Player 8+ et JavaScript activé pour voir cette vidéo.'}
								</div>
							</a>
							{/if}
							{foreach $offer->images key=image_key item=image name=images}
							<a rel="group-picture-{$offer_key}" href="{$link->getImageLink($image.product_link_rewrite, $image.imgIds, 'pictofferfancy')}" class="fancybox to_defile" style="display:{if $smarty.foreach.images.index == 1}block{else}none{/if}">
								<img src="{$link->getImageLink($image.product_link_rewrite, $image.imgIds, 'pictoffer')}" alt="" class="pict-product-one pict_product_{$image_key}" width="{$pictofferSize.width}" height="{$pictofferSize.height}">
							</a>
							{/foreach}
							{if $offer->video && !$offer->video_forward}
							<a href="#" class="video_iframe to_defile">
								<div id="ytapiplayer">
									{l s='Vous avez besoin de Flash Player 8+ et JavaScript activé pour voir cette vidéo.'}
								</div>
							</a>
							{/if}
							<i class="sprite loupe" {if $offer->video}style="display: none"{/if}></i>
							<div class="other-view">
								<ul>
									{foreach $offer->images key=image_key_tbs item=image}
									<li class="tbs_{$image_key_tbs}"><a href="#"><img src="{$link->getImageLink('offer', $image.imgIds, 'pictofferthumbs')}" alt="" width="{$pictofferthumbsSize.width}" height="{$pictofferthumbsSize.height}"></a></li>
									{/foreach}
									{if $offer->video}
									<li class="video_iframe_thumb">
										<a href="http://youtu.be/{$offer->video}" target="_blank">
											<i class="sprite video_ico">{l s='Vidéo du produit'}</i>
											<span>{l s='Vidéo'}</span>
										</a>
									</li>
									{/if}
								</ul>
							</div>
						</div>
						<div id="tabContainer">
								{if $offer->composition}
								<div id="more_info_tabs">
									<ul>
										<li id="tabHeader_1">{l s='Description'}</li>
										<li id="tabHeader_2">{l s='Composition'}</li>
									</ul>
								</div><!-- END#tabs -->
								{/if}
								<div id="more_info_sheets">
									<div class="tabpage" id="tabpage_1">
										<p>{$offer->description|escape:'htmlall':'UTF-8'|nl2br}</p>
									</div>
									<div class="tabpage" id="tabpage_2">
								<p>{$offer->composition|escape:'htmlall':'UTF-8'|nl2br}</p>
									</div>
								</div>
						</div><!-- END#tabContainer -->
					</div><!-- End#left-side -->
					<div id="right-side">
						<h2>{$offer->name|escape:'htmlall':'UTF-8'}</h2>
						<h3>{$offer->description_short|escape:'htmlall':'UTF-8'|nl2br}</h3>
						<p>{l s='à partir de'}</p>
						<div class="price-container clearfix">
							{assign var='price' value={convertPrice price=$offer->prices['min_price']}}
							{assign var='price_reduce' value={convertPrice price=$offer->prices['min_price_reduce']}}
							{assign var='priceSplit' value=','|explode:$price}
							<p class="price">{$priceSplit[0]}<span class="cts-minimify">,{$priceSplit[1]}</span></p>
							<p class="price-red">{$priceSplit[0]}<span class="cts-minimify">,{$priceSplit[1]}</span></p>
						</div>
						<div>
						{if $offer->prices['reduction']}
						<span class="original-price">{$price_reduce}</span>
						{/if}
						{if $offer->prices['reduction'] AND $offer->prices['reduction'].reduction_type == 'percentage'}
							<span class="remise">-{$offer->prices['reduction'].reduction*100}%</span>
						{/if}
						</div>
						{if $offer->nbProductsAlreadyBuyAll > 0}
							{if $offer->nbProductsAlreadyBuyAll > 1}
							<p class="product-sell">{l s='Déjà'} {$offer->nbProductsAlreadyBuy} {l s='produits achetés !'}</p>
							{else}
							<p class="product-sell">{l s='Déjà'} {$offer->nbProductsAlreadyBuy} {l s='produit acheté !'}</p>
							{/if}
						{/if}
						<a href="{$offer->offerLink}" class="sprite acheter"></a>
						<a href="{$offer->offerLink}" class="offer-to-friends"><i class="sprite cadeau"></i>{l s='Offrez-le à un ami'}</a>
						<p class="share-with-friends">{l s='Partager avec mes amis'}</p>
						<ul class="addthis_toolbox addthis_default_style share" addthis:ui_language="fr" addthis:url="{$offer->offerLink}" addthis:title="{l s='Découvrez l\'offre du jour sur #Only24h :'} {$offer->name|escape:'htmlall':'UTF-8'}" addthis:description="{$offer->description_short|escape:'htmlall':'UTF-8'}">
							<li><a class="addthis_button_facebook at300b"></a></li>
							<li><a class="addthis_button_twitter"><i class="sprite tw"></i></a></li>
							<li><a class="addthis_button_email" addthis:ui_language="fr"></a></li>
						</ul>
						<script text="text/javascript">
						var addthis_share = {ldelim}
							templates : {ldelim}
								twitter : "{ldelim}{ldelim}title{rdelim}{rdelim} {ldelim}{ldelim}url{rdelim}{rdelim}"
							{rdelim}
						{rdelim}
						</script>
					</div><!-- End#right-side -->
				</div><!-- End.product-display -->
				{/foreach}
			</div>
		</div>
		<a href="" title="" class="container-next control">
			<i class="sprite next"></i>		
		</a>
	</div><!-- End#product-highlight -->
{if $module.offers|@count gt 1}
	<div id="other-product" class="clearfix">
		<p class="other-product-title">{l s='Les autres offres'}</p>
		<ul>
		{foreach $module.offers key=offer_key item=offer}
			{assign var='image' value=$offer->images[0]['imgIds']}
			{assign var='product_link_rewrite' value=$offer->images[0]['product_link_rewrite']}
			{assign var='price' value={convertPrice price=$offer->prices['min_price']}}
			{assign var='priceSplit' value=','|explode:$price}
			{assign var='price_reduce' value={convertPrice price=$offer->prices['min_price_reduce']}}
			<li {if $offer_key eq 0}style="display: none"{/if}>
				<a href="#" id="visuel-{$offer_key}">
					<div class="visuel-other-product"><img src="{$link->getImageLink($product_link_rewrite, $image, 'pictotheroffer')}" alt="" width="{$pictotherofferSize.width}" height="{$pictotherofferSize.height}"></div>
					<div class="visuel-other-product-hover">
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
<!-- / MODULE {$module.module_name} -->
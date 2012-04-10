<div id="highlight">
	{include file="$tpl_dir./breadcrumb-hightlight.tpl"}
	<i class="sprite border-highlight"></i>
	<span class="highlight-title-product">{l s='Le catalogue'}</span>
	<h1 class="clearfix">{l s='Retrouvez ici l\'ensemble de nos offres'}</h1>
	<script>
		$(function(){
			$('#category').bind('change', function () {
				var id_category = $(this).val();
					if (id_category)
						window.location = baseDir + 'flashsalescatalog?category=' + id_category{if isset($smarty.get.old)}+ '&old=1'{/if};
					return false;
				});
			});
	</script>
	<div class="clear">
		<label for="category" class="select">{l s='Catégorie :'}</label>
		<select id="category" name="category">
			<option value="0" {if $current_category eq 0}selected="selected"{/if}>{l s='Toutes les offres'}</option>
			{foreach $categories item=category}
			<option value="{$category.id_flashsales_category}" {if $current_category eq $category.id_flashsales_category}selected="selected"{/if}>{$category.name}</option>
			{/foreach}
		</select>
		<form action="{$link->getPageLink('flashsalescatalog.php', true)}" method="post">
			<input type="text" name="search_text" placeholder="{l s='Recherche...'}" value="{if isset($smarty.post.search_text)}{$smarty.post.search_text|htmlentities:$ENT_QUOTES:'utf-8'|stripslashes}{/if}">
			{if isset($smarty.get.old)}
			<input type="hidden" name="old" value="1">
			{/if}
			<input type="hidden" name="category" value="{$current_category}">
			<input type="submit" name="SubmitOfferSearch" class="sprite send">
		</form>
	</div>
</div>
{include file="$tpl_dir./errors.tpl"}
{if isset($mailalert_confirm)}
<p class="success">{l s='Vous êtes inscrit à l\'alerte de remise en vente de cette offre.'}</p>
{/if}
<div id="list-product">
	{if $offers|@count eq 0}
	<p class="warning"><i class="sprite warning_ico"></i> {l s='Aucune offre dans cette catégorie.'}</p>
	{/if}
	{foreach $offers item=offer}
	{assign var='price' value={convertPrice price=$offer->prices['min_price']}}
	{assign var='price_reduce' value={convertPrice price=$offer->prices['min_price_reduce']}}
	{assign var='priceSplit' value=','|explode:$price}
	{assign var='image' value=$offer->images[0]['imgIds']}
	{assign var='product_link_rewrite' value=$offer->images[0]['product_link_rewrite']}
	<div class="catalogue-product">
		<div class="catalogue-left-side">
			<div class="picture-catalogue">
				<img src="{$link->getImageLink($product_link_rewrite, $image, 'pictotheroffer')}" class="pic-catalogue" width="{$pictofferSize.width}" height="{$pictofferSize.height}">
			</div>
			<h2 class="catalogue-title-product">{$offer->name|escape:'htmlall':'UTF-8'}</h2>
			<h3 class="catalogue-desc-product">{$offer->description|strip_tags}</h3>
			{if $offer->nbProductsAlreadyBuy > 0}
			<p class="catalogue-sell-product">{$offer->nbProductsAlreadyBuy} {l s='produits achetés !'}</p>
			{/if}
		<a href="{$link->getPageLink('flashsalesoffer.php')}?id_flashsales_offer={$offer->id}" class="sprite acheter {if $offer->date_start neq $current_period}offline{/if}"></a>
		</div>
		<div class="catalogue-right-side">
			<p>{l s='à partir de'}</p>
				<p class="price">{$priceSplit[0]}<span class="cts-minimify">,{$priceSplit[1]}</span></p>
			{if $price neq $price_reduce}
			<span class="original-price">{$price_reduce}</span>
			{/if}
			{if $offer->prices['reduction'] AND $offer->prices['reduction'].reduction_type == 'percentage'}
				<span class="remise">-{$offer->prices['reduction'].reduction*100}%</span>
			{/if}
		</div>
		<div class="alerte-mail" style="display: none">
			<p class="alerte-mail-title">{l s='J\'achète'}</p>
			<p class="alerte-mail-what">{l s='Laissez-nous votre email, nous vous recontacterons lors de la prochaine mise en vente de ce produit.'}</p>
			<form action="{$link->getPageLink('flashsalescatalog.php', true)}" method="post">
				<input type="email" placeholder="Email" required name="customer_email">
				<input type="hidden" name="id_flashsales_offer" value="{$offer->id}">
				<input type="submit" name="SubmitMailAlert" class="sprite">
			</form>
			<a href="" title="close" class="close-alerte-mail"><i class="sprite close-alerte-mail-i"></i></a>
		</div>
	</div><!-- End#catalogue-product -->
	{/foreach}
</div>
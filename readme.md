# MODULE FLASHSAlES

## Installation
1. Cd to your folder : cd /path/to/your/folder/
2. Create a symbolic link to flashsalesoffer.php : ln -s absolute/path/to/your/folder/modules/flashsales/frontend/flashsalesoffer.php absolute/path/to/your/folder/flashsalesoffer.php
3. Create a symbolic link to flashsalescatalog.php : ln -s absolute/path/to/your/folder/modules/flashsales/frontend/.php absolute/path/to/your/folder/flashsalescatalog.php
4. Create a symbolic link to controllers/FlashSalesOfferController.php : ln -s absolute/path/to/your/folder/modules/flashsales/frontend/controllers/FlashSalesOfferController.php absolute/path/to/your/folder/controllers/FlashSalesOfferController.php
5. Create a symbolic link to controllers/FlashSalesCatalogController.php : ln -s absolute/path/to/your/folder/modules/flashsales/frontend/controllers/FlashSalesCatalogController.php absolute/path/to/your/folder/controllers/FlashSalesCatalogController.php
6. Create a symbolic link to flashsalesoffer.tpl : ln -s absolute/path/to/your/folder/modules/flashsales/frontend/flashsalesoffer.tpl absolute/path/to/your/folder/themes/prestashop/flashsalesoffer.tpl
7. Create a symbolic link to flashsalescatalog.tpl : ln -s absolute/path/to/your/folder/modules/flashsales/frontend/.tpl absolute/path/to/your/folder/themes/prestashop/flashsalescatalog.tpl
*Make sure you have activate FollowSymlink in your vhost*
5. Install module in your backoffice

## Tips
* You can define a canonical URL for flashsalesoffer in your backoffice (e.g: /backoffice/index.php?tab=AdminMeta&addmeta)

## Uninstallation
* The uninstaller delete automatically symbolic link.
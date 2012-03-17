# MODULE FLASHSAlES

## Installation
1. Cd to your folder : cd /path/to/your/folder/
2. Create a symbolic link to flashsalesoffer.php : ln -s absolute/path/to/your/folder/modules/flashsales/frontend/flashsalesoffer.php absolute/path/to/your/folder/flashsalesoffer.php
3. Create a symbolic link to controllers/FlashSalesOffer.php : ln -s absolute/path/to/your/folder/modules/flashsales/frontend/controllers/FlashSalesOfferController.php absolute/path/to/your/folder/controllers/FlashSalesOfferController.php
*Make sure you have activate FollowSymlink in your vhost*
4. Install module in your backoffice

## Tips
* You can define a canonical URL for flashsalesoffer in your backoffice (e.g: /backoffice/index.php?tab=AdminMeta&addmeta)

## Uninstallation
* The uninstaller delete automatically symbolic link.
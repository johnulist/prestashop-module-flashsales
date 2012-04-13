#!/bin/bash

# Variables
path_website='/Users/Nyx/Sites/only24/templating'
theme='only24h'

## Delete old
# Root
sudo rm $path_website/flashsalesoffer.php
sudo rm $path_website/flashsalescatalog.php
# Controllers
sudo rm $path_website/controllers/FlashSalesOfferController.php
sudo rm $path_website/controllers/FlashSalesCatalogController.php
# TPL
sudo rm $path_website/themes/$theme/flashsalesoffer.tpl
sudo rm $path_website/themes/$theme/flashsalescatalog.tpl

## Create Symlinks
# Root
sudo ln -s $path_website/modules/flashsales/frontend/flashsalesoffer.php $path_website/flashsalesoffer.php
sudo ln -s $path_website/modules/flashsales/frontend/flashsalescatalog.php $path_website/flashsalescatalog.php
# Controllers
sudo ln -s $path_website/modules/flashsales/frontend/controllers/FlashSalesOfferController.php $path_website/controllers/FlashSalesOfferController.php
sudo ln -s $path_website/modules/flashsales/frontend/controllers/FlashSalesCatalogController.php $path_website/controllers/FlashSalesCatalogController.php
# TPL
sudo ln -s $path_website/modules/flashsales/frontend/flashsalesoffer.tpl $path_website/themes/$theme/flashsalesoffer.tpl
sudo ln -s $path_website/modules/flashsales/frontend/flashsalescatalog.tpl $path_website/themes/$theme/flashsalescatalog.tpl

## CHMOD
# Root
sudo chmod 777 $path_website/flashsalesoffer.php $path_website/flashsalescatalog.php
# Controllers
sudo chmod 777 $path_website/controllers/FlashSalesOfferController.php $path_website/controllers/FlashSalesCatalogController.php
# TPL
sudo chmod 777 $path_website/themes/$theme/flashsalesoffer.tpl $path_website/themes/$theme/flashsalescatalog.tpl
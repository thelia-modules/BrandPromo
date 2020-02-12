<?php


namespace BrandPromo\Controller;


use BrandPromo\Form\BrandPromoForm;
use ClassicRide\ClassicRide;
use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\Translation\Translator;
use Thelia\Form\Exception\FormValidationException;
use Thelia\Model\Product;
use Thelia\Model\ProductQuery;
use Thelia\Model\ProductSaleElements;
use Thelia\Model\ProductSaleElementsQuery;

class BrandPromoController extends BaseAdminController
{
    public function toolShow()
    {
        return $this->render('brand-promotion');
    }

    public function setBrandToPromo($value) {
        $form = new BrandPromoForm($this->getRequest());

        try {
            $validForm = $this->validateForm($form);
            $data = $validForm->getData();
        } catch (FormValidationException $e) {
            $this->setupFormErrorContext(
                Translator::getInstance()->trans(
                    "Error",
                    [],
                    ClassicRide::DOMAIN_NAME
                ),
                $e->getMessage(),
                $form
            );
            return $this->generateErrorRedirect($form);
        } catch (\Exception $e) {
            $this->setupFormErrorContext(
                Translator::getInstance()->trans(
                    "Error",
                    [],
                    ClassicRide::DOMAIN_NAME
                ),
                $e->getMessage(),
                $form
            );
            return $this->generateErrorRedirect($form);
        }

        $products = ProductQuery::create()
            ->filterByBrandId($data['brand'])
            ->find()
            ;

        /** @var Product $product */
        foreach ($products as $product) {
            $pseList = ProductSaleElementsQuery::create()
                ->filterByProduct($product)
                ->find()
                ;

            /** @var ProductSaleElements $pse */
            foreach ($pseList as $pse) {
                $pse
                    ->setPromo($value)
                    ->save()
                ;
            }
        }

        return $this->generateRedirect('/admin/module/brandpromo/tool');
    }
}
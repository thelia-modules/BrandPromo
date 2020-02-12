<?php


namespace BrandPromo\Form;


use BrandPromo\BrandPromo;
use Propel\Runtime\ActiveQuery\Criteria;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Thelia\Core\Translation\Translator;
use Thelia\Form\BaseForm;
use Thelia\Model\Brand;
use Thelia\Model\BrandQuery;
use Thelia\Model\Lang;

class BrandPromoForm extends BaseForm
{
    protected function buildForm()
    {
        $this->formBuilder
            ->add(
                'brand',
                ChoiceType::class,
                [
                    'label' => Translator::getInstance()->trans("Brand", array(),BrandPromo::DOMAIN_NAME),
                    'choices' => $this->getAllBrands(),
                    'choices_as_values' => true,
                    'required' => true,
                ]
            )
        ;
    }

    protected function getAllBrands()
    {
        /** @var Lang $lang */
        $lang = $this->request->getSession() ? $this->request->getSession()->getLang(true) : $this->request->lang = Lang::getDefaultLanguage();

        $brands = BrandQuery::create()
            ->joinWithI18n($lang->getLocale(), Criteria::INNER_JOIN)
            ->find();

        $tabData = [];

        /** @var Brand $brand */
        foreach ($brands as $brand) {
            $tabData[$brand->getTitle()] = $brand->getId();
        }

        return $tabData;
    }

    /**
     * @return string the name of the form. This name need to be unique.
     */
    public function getName()
    {
        return 'brandpromo_form';
    }
}
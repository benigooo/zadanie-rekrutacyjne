<?php
if (!defined('_PS_VERSION_')) {
    exit;
}

use PrestaShop\PrestaShop\Adapter\Category\CategoryProductSearchProvider;
use PrestaShop\PrestaShop\Core\Module\WidgetInterface;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchContext;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchQuery;
use PrestaShop\PrestaShop\Core\Product\Search\SortOrder;

class FeaturedCategories extends Module implements WidgetInterface
{
    private $templateFile;

    public function __construct()
    {
        $this->name = 'featuredcategories';
        $this->author = '';
        $this->version = '1.0';
        $this->need_instance = 0;

        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->trans('Featured categories', [], 'Modules.Featuredcategories.Admin');
        $this->description = $this->trans('', [], 'Modules.Featuredcategories.Admin');

        $this->templateFile = 'module:featuredcategories/views/templates/hook/featuredcategories.tpl';
    }

    public function install()
    {
        $this->_clearCache('*');

        return parent::install()
            && $this->registerHook('header')
            && $this->registerHook('displayHome');
    }

    public function uninstall()
    {
        $this->_clearCache('*');

        return parent::uninstall();
    }

    public function _clearCache($template, $cache_id = null, $compile_id = null)
    {
        parent::_clearCache($this->templateFile);
    }

    public function getContent()
    {
        $output = '';
        $errors = [];

        if (Tools::isSubmit('submitFeaturedCategories')) {

            $cat_1 = Tools::getValue('HOME_FEATURED_CAT_1');
            if (!Validate::isInt($cat_1) || $cat_1 <= 0) {
                $errors[] = $this->trans('The category ID is invalid. Please choose an existing category ID.', [], 'Modules.Featuredcategories.Admin');
            }
            $cat_2 = Tools::getValue('HOME_FEATURED_CAT_2');
            if (!Validate::isInt($cat_2) || $cat_2 <= 0) {
                $errors[] = $this->trans('The category ID is invalid. Please choose an existing category ID.', [], 'Modules.Featuredcategories.Admin');
            }
            $cat_3 = Tools::getValue('HOME_FEATURED_CAT_3');
            if (!Validate::isInt($cat_3) || $cat_3 <= 0) {
                $errors[] = $this->trans('The category ID is invalid. Please choose an existing category ID.', [], 'Modules.Featuredcategories.Admin');
            }

            if (count($errors)) {
                $output = $this->displayError(implode('<br />', $errors));
            } else {
                Configuration::updateValue('HOME_FEATURED_CAT_1', (int) $cat_1);
                Configuration::updateValue('HOME_FEATURED_CAT_2', (int) $cat_2);
                Configuration::updateValue('HOME_FEATURED_CAT_3', (int) $cat_3);

                $this->_clearCache('*');

                $output = $this->displayConfirmation($this->trans('The settings have been updated.', [], 'Admin.Notifications.Success'));
            }
        }

        return $output . $this->renderForm();
    }

    public function renderForm()
    {

        $root = Category::getRootCategory();

        for($i = 1; $i < 4; $i++){
            $tree = new HelperTreeCategories('HOME_FEATURED_CAT_'.$i);
            $tree->setUseCheckBox(false)
                ->setAttribute('is_category_filter', $root->id)
                ->setRootCategory($root->id)
                ->setSelectedCategories(array((int)Configuration::get('HOME_FEATURED_CAT_'.$i)))
                ->setInputName('HOME_FEATURED_CAT_'.$i);
            $categoryTreeCol[$i] = $tree->render();
        }

        $fields_form = [
            'form' => [
                'legend' => [
                    'title' => $this->trans('Settings', [], 'Admin.Global'),
                    'icon' => 'icon-cogs',
                ],
                'input' => [
                    [
                        'type' => 'html',
                        'label' => $this->trans('Kategoria wyświetlana w 1 kolumnie', [], 'Modules.Featuredproducts.Admin'),
                        'html_content'  => $categoryTreeCol[1]
                    ],
                    [
                        'type' => 'html',
                        'label' => $this->trans('Kategoria wyświetlana w 2 kolumnie', [], 'Modules.Featuredproducts.Admin'),
                        'html_content'  => $categoryTreeCol[2]
                    ],
                    [
                        'type' => 'html',
                        'label' => $this->trans('Kategoria wyświetlana w 3 kolumnie', [], 'Modules.Featuredproducts.Admin'),
                        'html_content'  => $categoryTreeCol[3]
                    ],
                ],
                'submit' => [
                    'title' => $this->trans('Save', [], 'Admin.Actions'),
                ],
            ],
        ];

        $lang = new Language((int) Configuration::get('PS_LANG_DEFAULT'));

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitFeaturedCategories';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false) . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = [
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        ];

        return $helper->generateForm([$fields_form]);
    }

    public function getConfigFieldsValues()
    {
        return [
            'HOME_FEATURED_CAT_1' => Tools::getValue('HOME_FEATURED_CAT_1', (int) Configuration::get('HOME_FEATURED_CAT_1')),
            'HOME_FEATURED_CAT_2' => Tools::getValue('HOME_FEATURED_CAT_2', (int) Configuration::get('HOME_FEATURED_CAT_2')),
            'HOME_FEATURED_CAT_3' => Tools::getValue('HOME_FEATURED_CAT_3', (int) Configuration::get('HOME_FEATURED_CAT_3')),
        ];
    }

    public function hookHeader(){
        $this->context->controller->registerStylesheet('css', $this->_path.'/views/css/styles.css', ['media' => 'all', 'priority' => 555]);
        $this->context->controller->registerJavascript('js', $this->_path.'/views/js/products-carousel.js', ['media' => 'all', 'priority' => 555]);
    }

    public function renderWidget($hookName = null, array $configuration = [])
    {

        if (!$this->isCached($this->templateFile, $this->getCacheId('featuredcategories'))) {
            $variables = $this->getWidgetVariables($hookName, $configuration);

            if (empty($variables)) {
                return false;
            }

            $this->smarty->assign($variables);
        }

        return $this->fetch($this->templateFile, $this->getCacheId('featuredcategories'));
    }

    public function getWidgetVariables($hookName = null, array $configuration = [])
    {

        for($i = 1; $i < 4; $i++){
            $category_id = $this->getConfigFieldsValues()['HOME_FEATURED_CAT_'.$i];
            $categories[$i]['products'] = $this->getProductsFromCategory($category_id);

            $category = new Category ($category_id, Context::getContext()->language->id);
            $categories[$i]['name'] = $category->name;
            $categories[$i]['link'] = Context::getContext()->link->getCategoryLink($category_id);
        }

        if (!empty($categories)) {
            return [
                'categories' => $categories
            ];
        }

        return false;
    }

    protected function getProductsFromCategory($category_id)
    {
        $category = new Category((int) $category_id);

        $searchProvider = new CategoryProductSearchProvider(
            $this->context->getTranslator(),
            $category
        );

        $context = new ProductSearchContext($this->context);

        $query = new ProductSearchQuery();

        $nProducts = 10;

        $query
            ->setResultsPerPage($nProducts)
            ->setPage(1)
        ;

        $query->setSortOrder(new SortOrder('product', 'position', 'asc'));

        $result = $searchProvider->runQuery(
            $context,
            $query
        );

        $assembler = new ProductAssembler($this->context);

        $presenterFactory = new ProductPresenterFactory($this->context);
        $presentationSettings = $presenterFactory->getPresentationSettings();
        $presenter = $presenterFactory->getPresenter();

        $products_for_template = [];

        foreach ($result->getProducts() as $rawProduct) {
            $products_for_template[] = $presenter->present(
                $presentationSettings,
                $assembler->assembleProduct($rawProduct),
                $this->context->language
            );
        }

        return $products_for_template;
    }

    protected function getCacheId($name = null)
    {
        $cacheId = parent::getCacheId($name);
        if (!empty($this->context->customer->id)) {
            $cacheId .= '|' . $this->context->customer->id;
        }

        return $cacheId;
    }
}

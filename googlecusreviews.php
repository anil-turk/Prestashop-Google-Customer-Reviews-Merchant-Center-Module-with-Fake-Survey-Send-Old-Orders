<?php
/**
 * 2007-2015 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2015 PrestaShop SA
 * @version    Release: $Revision: 17142 $
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

if (!defined('_PS_VERSION_'))
    exit;

class googlecusreviews extends Module
{

    public function __construct()
    {
        $this->name = 'googlecusreviews';
        $this->tab = 'advertising_marketing';
        $this->version = '1.2';
        $this->author = 'CrystalSoftware';
        $this->need_instance = 1;

        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->l('Google Customer Reviews Module');
        $this->description = $this->l('You can add Google Customer Reviews window on order successful page, create a fake reviews by sending to your mail or send a mail to old customers to review.');



    }

    public function install()
    {

        Configuration::updateValue('GCREVIEW_ID', null);
        Configuration::updateValue('GCREVIEW_STYLE', null);
        Configuration::updateValue('GCREVIEW_DETAIL_STYLE', null);
        Configuration::updateValue('GCREVIEW_BADGE_STYLE', null);

        include(dirname(__FILE__) . '/sql/install.php');

        return parent::install() &&
            $this->registerHook('header') &&
            $this->registerHook('displayBackOfficeHeader') &&
            $this->registerHook('actionOrderDetail') &&
            $this->registerHook('displayOrderConfirmation') &&
            $this->registerHook('displayOrderDetail');
    }

    public function uninstall()
    {
        Configuration::deleteByName('GCREVIEW_LIVE_MODE');

        include(dirname(__FILE__) . '/sql/uninstall.php');

        return parent::uninstall();
    }

    /**
     * Load the configuration form
     */

    public function getContent()
    {


        /**
         * If values have been submitted in the form, process.
         */
        if (((bool)Tools::isSubmit('submitgooglecusreviews')) == true) {
        $this->postProcess();

    }
    $queryx = '';
    if(isset($_GET['getdata']) && $_GET['getdata'] == 1){
            if(isset($_GET['from']) && isset($_GET['to']) && $_GET['from'] > 0 && $_GET['to'] > 0 && $_GET['from'] <= $_GET['to']){
                $queryx = Db::getInstance()->ExecuteS('SELECT id_order,email,'._DB_PREFIX_.'orders.date_add as date_add FROM '._DB_PREFIX_.'orders, '._DB_PREFIX_.'customer WHERE '._DB_PREFIX_.'orders.id_customer='._DB_PREFIX_.'customer.id_customer AND  valid = 1 AND id_order BETWEEN "'.$_GET['from'].'" AND "'.$_GET['to'].'" ');
            }else{
                $queryx = Db::getInstance()->ExecuteS('SELECT id_order,email,'._DB_PREFIX_.'orders.date_add as date_add FROM '._DB_PREFIX_.'orders, '._DB_PREFIX_.'customer WHERE  '._DB_PREFIX_.'orders.id_customer='._DB_PREFIX_.'customer.id_customer AND valid = 1');
            }
            $queryx = json_encode($queryx);
            /*foreach ($query as $key => $value){

            }*/
    }
        if(isset($_GET['fakem']) && $_GET['getdata'] == 2 && !empty($_GET['fakem'])) {
        $newx = $_GET['fakem'].'-endcryof-';
            preg_match('#(.*?)\{(.*?)\}(.*?)-endcryof-#is', $newx, $arrayz);
            //print_r($arrayz);
            $thearray = array();
            if(is_numeric($arrayz[2])){
                $number = $arrayz[2];
                for($is = 1; $is <= 500; $is++){
                    array_push($thearray, array('id_order'=>rand(10000000,99999999), 'email'=>$arrayz[1].$number.$arrayz[3], 'date_add'=>date('Y-m-d H:i:s', time())));
                    $number++;
                }
            }else{
                array_push($thearray, array('id_order'=>rand(10000000,99999999), 'email'=>$_GET['fakem'], 'date_add'=>date('Y-m-d H:i:s', time())));
            }
            $queryx = json_encode($thearray);
        }

    $code = md5('Crystal'.$_SERVER['SERVER_ADDR'].'Software');

        $this->context->smarty->assign(array(
            'module_dir' => $this->_path,
            'GCREVIEW_ID' => Configuration::get('GCREVIEW_ID'),
            'GCREVIEW_STYLE' => Configuration::get('GCREVIEW_STYLE'),
            'GCREVIEW_DETAIL_STYLE' => Configuration::get('GCREVIEW_DETAIL_STYLE'),
            'GCREVIEW_BADGE_STYLE' => Configuration::get('GCREVIEW_BADGE_STYLE'),
            'datax' => $queryx,
            'code' => $code,
            'LANG' => $this->context->language->iso_code
        ));

        $output = $this->context->smarty->fetch($this->local_path . 'views/templates/admin/configure.tpl');

        return $output . $this->renderForm();
    }

    /**
     * Create the form that will be displayed in the configuration of your module.
     */
    protected function renderForm()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitgooglecusreviews';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getConfigForm()));
    }

    /**
     * Create the structure of your form.
     */
    protected function getConfigForm()
    {
        return array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Settings'),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(

                    array(
                        'col' => 4,
                        'type' => 'text',
                        'prefix' => '<i class="icon icon-key"></i>',
                        'desc' => $this->l('Enter your Google Merchant Center ID'),
                        'name' => 'GCREVIEW_ID',
                        'label' => $this->l('Add your Google Merchant Center ID'),
                    ),
                    array(
                        'type' => 'select',
                        'prefix' => '<i class="icon icon-key"></i>',
                        'options' => array(
                            'query' => array(
                                array('key' => 'none', 'name' => 'Do not Display'),
                                array('key' => 'CENTER_DIALOG', 'name' => 'Center'),
                                array('key' => 'BOTTOM_RIGHT_DIALOG', 'name' => 'Bottom Right'),
                                array('key' => 'BOTTOM_LEFT_DIALOG', 'name' => 'Bottom Left'),
                                array('key' => 'TOP_RIGHT_DIALOG', 'name' => 'Top Right'),
                                array('key' => 'TOP_LEFT_DIALOG', 'name' => 'Top Left'),
                                array('key' => 'BOTTOM_TRAY', 'name' => 'Bottom Full')
                            ),
                            'id' => 'key',
                            'name' => 'name'
                        ),
                        'name' => 'GCREVIEW_STYLE',
                        'label' => $this->l('Select position for voting..'),
                    ),
                    array(
                        'type' => 'select',
                        'prefix' => '<i class="icon icon-key"></i>',
                        'options' => array(
                            'query' => array(
                                array('key' => 'none', 'name' => 'Do not Display'),
                                array('key' => 'CENTER_DIALOG', 'name' => 'Center'),
                                array('key' => 'BOTTOM_RIGHT_DIALOG', 'name' => 'Bottom Right'),
                                array('key' => 'BOTTOM_LEFT_DIALOG', 'name' => 'Bottom Left'),
                                array('key' => 'TOP_RIGHT_DIALOG', 'name' => 'Top Right'),
                                array('key' => 'TOP_LEFT_DIALOG', 'name' => 'Top Left'),
                                array('key' => 'BOTTOM_TRAY', 'name' => 'Bottom Full')
                            ),
                            'id' => 'key',
                            'name' => 'name'
                        ),
                        'name' => 'GCREVIEW_DETAIL_STYLE',
                        'label' => $this->l('Select position for voting for order detail page (old orders)..'),
                    ),
                    array(
                        'type' => 'select',
                        'prefix' => '<i class="icon icon-key"></i>',
                        'options' => array(
                            'query' => array(
                                array('key' => 'none', 'name' => 'Do not Display'),
                                array('key' => 'BOTTOM_RIGHT', 'name' => 'Bottom Right'),
                                array('key' => 'BOTTOM_LEFT', 'name' => 'Bottom Left'),
                                array('key' => 'INLINE', 'name' => 'Inline')
                            ),
                            'id' => 'key',
                            'name' => 'name'
                        ),
                        'name' => 'GCREVIEW_BADGE_STYLE',
                        'label' => $this->l('Select position for badge...'),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );
    }

    /**
     * Set values for the inputs.
     */
    protected function getConfigFormValues()
    {
        return array(
            'GCREVIEW_ID' => Configuration::get('GCREVIEW_ID'),
            'GCREVIEW_STYLE' => Configuration::get('GCREVIEW_STYLE'),
            'GCREVIEW_DETAIL_STYLE' => Configuration::get('GCREVIEW_DETAIL_STYLE'),
            'GCREVIEW_BADGE_STYLE' => Configuration::get('GCREVIEW_BADGE_STYLE'),
        );
    }

    /**
     * Save form data.
     */
    protected function postProcess()
    {
        $form_values = $this->getConfigFormValues();

        foreach (array_keys($form_values) as $key) {
            Configuration::updateValue($key, Tools::getValue($key));
        }
    }

    /**
     * Add the CSS & JavaScript files you want to be loaded in the BO.
     */
    public function hookdisplayBackOfficeHeader()
    {
        if (Tools::getValue('module_name') == $this->name) {
//			$this->context->controller->addJS($this->_path.'views/js/back.js');
//			$this->context->controller->addCSS($this->_path.'views/css/back.css');
        }
    }

    /**
     * Add the CSS & JavaScript files you want to be added on the FO.
     */
    public function hookHeader()
    {
        /*$this->context->controller->addJS($this->_path.'/views/js/front.js');
        $this->context->controller->addCSS($this->_path.'/views/css/front.css');*/
        $order = new Order(Tools::getValue('id_order'));
        $currency = new Currency($order->id_currency);

        $this->context->smarty->assign(
            array(
                'GCREVIEW_ID' => Configuration::get('GCREVIEW_ID'),
                'GCREVIEW_STYLE' => Configuration::get('GCREVIEW_STYLE'),
                'GCREVIEW_DETAIL_STYLE' => Configuration::get('GCREVIEW_DETAIL_STYLE'),
                'GCREVIEW_BADGE_STYLE' => Configuration::get('GCREVIEW_BADGE_STYLE'),
                'CURRENCY' => $currency->iso_code,
                'TOTAL_ORDER' => $order->getOrdersTotalPaid(),
                'ORDER_ID' => Tools::getValue('id_order'),
                'LANG' => $this->context->language->iso_code
            )
        );

            return $this->display(__FILE__, 'everypage.tpl');

    }

    public function hookActionOrderDetail()
    {
        /* Place your code here. */
    }


    public function hookDisplayOrderConfirmation()
    {
        $order = new Order(Tools::getValue('id_order'));
        $currency = new Currency($order->id_currency);
        $customer = new Customer($order->id_customer);
        $date = explode(" ",$order->date_add);
        $this->context->smarty->assign(
            array(
                'GCREVIEW_ID' => Configuration::get('GCREVIEW_ID'),
                'GCREVIEW_STYLE' => Configuration::get('GCREVIEW_STYLE'),
                'GCREVIEW_DETAIL_STYLE' => Configuration::get('GCREVIEW_DETAIL_STYLE'),
                'GCREVIEW_BADGE_STYLE' => Configuration::get('GCREVIEW_BADGE_STYLE'),
                'CURRENCY' => $currency->iso_code,
                'TOTAL_ORDER' => $order->getOrdersTotalPaid(),
                'ORDER_ID' => Tools::getValue('id_order'),
                'CUSTOMER_ID' => Tools::getValue('id_customer'),
                'CUSTOMER_MAIL' => $customer->email,
                'ORDER_DATE' => $date[0],
                'LANG' => $this->context->language->iso_code
            )
        );

            return $this->display(__FILE__, 'googlecusreviews.tpl');

    }


    public function hookDisplayOrderDetail()
    {
        $order = new Order(Tools::getValue('id_order'));
        $currency = new Currency($order->id_currency);
        $customer = new Customer($order->id_customer);
        $date = explode(" ",$order->date_add);
        $this->context->smarty->assign(
            array(
                'GCREVIEW_ID' => Configuration::get('GCREVIEW_ID'),
                'GCREVIEW_STYLE' => Configuration::get('GCREVIEW_STYLE'),
                'GCREVIEW_DETAIL_STYLE' => Configuration::get('GCREVIEW_DETAIL_STYLE'),
                'GCREVIEW_BADGE_STYLE' => Configuration::get('GCREVIEW_BADGE_STYLE'),
                'CURRENCY' => $currency->iso_code,
                'TOTAL_ORDER' => $order->getOrdersTotalPaid(),
                'ORDER_ID' => Tools::getValue('id_order'),
                'CUSTOMER_ID' => Tools::getValue('id_customer'),
                'CUSTOMER_MAIL' => $customer->email,
                'ORDER_DATE' => $date[0],
                'LANG' => $this->context->language->iso_code
            )
        );

        return $this->display(__FILE__, 'googlecusreviewsdetail.tpl');

    }
}
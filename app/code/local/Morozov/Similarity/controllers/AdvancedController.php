<?php
require_once Mage::getModuleDir('controllers', 'Mage_CatalogSearch') . DS . 'AdvancedController.php';

class Morozov_Similarity_AdvancedController
extends Mage_CatalogSearch_AdvancedController
{
    public function resultAction()
    {
        $this->loadLayout();
        try {
            try {
                Mage::getSingleton('catalogsearch/advanced')->addFilters($this->getRequest()->getQuery());
                if ($this->getSimilar()) {
                    $this->addSimilarFilters(Mage::getSingleton('catalogsearch/advanced'), $this->getSimilar());
                }
            } catch (Mage_Core_Exception $e) {
                if ($this->getSimilar() && $this->detectTermsNotSpecifiedMsg($e->getMessage())) {
                    $this->addSimilarFilters(Mage::getSingleton('catalogsearch/advanced'), $this->getSimilar());
                } else {
                    throw $e;
                }
            }
        } catch (Mage_Core_Exception $e) {
            Mage::getSingleton('catalogsearch/session')->addError($e->getMessage());
            $this->_redirectError(
                Mage::getModel('core/url')
                    ->setQueryParams($this->getRequest()->getQuery())
                    ->getUrl('*/*/')
            );
        }
        $this->_initLayoutMessages('catalog/session');
        $this->renderLayout();
    }

    protected function detectTermsNotSpecifiedMsg($message)
    {
        $res = stristr($message, Mage::helper('catalogsearch')->__('Please specify at least one search term.'));
        return $res;
    }

    protected function getSimilar()
    {
        $similar = $this->getRequest()->getParam($this->getAdvancedSearchHelper()->getSimilarVarName());
        return $similar;
    }

    protected function addSimilarFilters(Mage_CatalogSearch_Model_Advanced $advanced, $similar)
    {
        // @TODO: get all similar Products from the service
        /*
        if ($similar = $this->getSimilar()) {

            //$advanced->getProductCollection()
            //    ->addFieldToFilter('entity_id', ['in' => [340, 31660]])
            //;

            //
        } else {
            //throw new Mage_Core_Exception('Similar param is not specified');
        }
        */
    }

    protected function getAdvancedSearchHelper()
    {
        return Mage::helper('morozov_similarity/advancedSearch');
    }
}

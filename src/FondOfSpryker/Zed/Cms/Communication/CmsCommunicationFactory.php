<?php

namespace FondOfSpryker\Zed\Cms\Communication;

use FondOfSpryker\Zed\Cms\Communication\Form\CmsRedirectForm;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Store\Persistence\Base\SpyStoreQuery;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Cms\Communication\CmsCommunicationFactory as SprykerCmsCommunicationFactory;

/**
 * @method \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Cms\CmsConfig getConfig()
 */
class CmsCommunicationFactory extends SprykerCmsCommunicationFactory
{
    /**
     * @param array $formData
     * @param array $formOptions
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createCmsRedirectForm(array $formData = [], array $formOptions = [])
    {
        return $this->getFormFactory()->create(CmsRedirectForm::class, $formData, $formOptions);
    }

    /**
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getStore(): StoreTransfer
    {
        $store = Store::getInstance();
        $spyStoreQuery = SpyStoreQuery::create();
        $spyStore = $spyStoreQuery->filterByName($store->getStoreName())->findOne();

        $storeTransfer = new StoreTransfer();
        $storeTransfer->fromArray($spyStore->toArray(), true);

        return $storeTransfer;
    }
}

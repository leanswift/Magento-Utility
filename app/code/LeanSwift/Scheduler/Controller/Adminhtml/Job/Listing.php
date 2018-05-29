<?php

/**
 * Copyright © 2016 Wyomind. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace LeanSwift\Scheduler\Controller\Adminhtml\Job;

/**
 * Jobs listing action
 * @version 1.0.0
 */
class Listing extends \Magento\Backend\App\Action
{

    /**
     * @var string
     */
    protected $_aclResource = "job_listing";
    
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $_resultPageFactory = null;

    /**
     * @var \LeanSwift\Scheduler\Helper\HeartBeat
     */
    public $heartBeatHelper = null;

    /**
     * Class constructor
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \LeanSwift\Scheduler\Helper\HeartBeat $heartBeatHelper
     */
    public function __construct(
    \Magento\Backend\App\Action\Context $context,
            \Magento\Framework\View\Result\PageFactory $resultPageFactory,
            \LeanSwift\Scheduler\Helper\HeartBeat $heartBeatHelper
    )
    {
        $this->_resultPageFactory = $resultPageFactory;
        $this->heartBeatHelper = $heartBeatHelper;
        parent::__construct($context);
    }

    /**
     * Execute action
     */
    public function execute()
    {
        $this->heartBeatHelper->getLastHearBeatMessage();
        $resultPage = $this->_resultPageFactory->create();
        $resultPage->setActiveMenu("Magento_Backend::system");
        $resultPage->getConfig()->getTitle()->prepend(__('Job Configuration'));
        $resultPage->addBreadcrumb(__('LS Scheduler'), __('LS Scheduler'));
        return $resultPage;
    }

    
    /**
     * Is the action allowed?
     * @return boolean
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('LeanSwift_Scheduler::'.$this->_aclResource);
    }
    
}

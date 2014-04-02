<?php
namespace Concrete\Controller\Backend\UI;
abstract class Page extends \Concrete\Controller\Backend\UI {

	protected $page;

	public function __construct() {
		parent::__construct();
		$request = $this->request;
		$cID = $request->query->get('cID');
		if ($cID) {
			$page = Page::getByID($cID);
			$this->setPageObject($page);
			$request->setCurrentPage($this->page);
		}

	}

	public function setPageObject(Page $c) {
		$this->page = $c;
		$this->permissions = new Permissions($this->page);		
		$this->set('c', $this->page);
		$this->set('cp', $this->permissions);
	}

	public function getViewObject() {
		if ($this->permissions->canViewPage()) {
			return parent::getViewObject();
		}
		throw new Exception(t('Access Denied'));
	}

	public function action() {
		$url = call_user_func_array('parent::action', func_get_args());
		$url .= '&cID=' . $this->page->getCollectionID();
		return $url;
	}

}
	
<?php
App::uses('PagesController', 'Controller');

class PagesControllerTest extends ControllerTestCase {

	public $fixtures = array(
		'app.configuration',
		'app.user',
	);

	public function setUp() {
		@session_start();
		parent::setUp();
	}

	public function testDisplay() {

		$result = $this->testAction('/pages/services/online_marketing/email_marketing', array('return' => 'vars'));
		$this->assertEqual($result['page'], 'services');
		$this->assertEqual($result['subpage'], 'online_marketing');
		$this->assertEqual($result['companyName'], 'CrazySoft');
		$this->assertEqual($result['companyWebsiteURL'], 'http://crazycms.loc');
		$this->assertEqual($result['companyLogo'], '<span class="color-1">Crazy</span>Soft');
		$this->assertEqual($result['companyAddress'], 'Unit J501, 27-29 George Street, North Strathfield, NSW, 2137, Australia.');
		$this->assertEqual($result['sharedImage'], '&lt;span class=&quot;color-1&quot;&gt;Crazy&lt;/span&gt;Soft');
		$this->assertEqual($result['sharedSummary'], 'CrazySoft');
		$this->assertEqual($result['sharedUrl'], 'http://crazycms.loc');
		$this->assertEqual($result['sharedTitle'], 'CrazySoft - your first online solution');
		$this->assertEqual($result['title_for_layout'], 'CrazySoft');
		$this->assertEqual($result['metaKeywords'], 'crazy cms crazysoft web design development website seo email marketing e-commerce');
		$this->assertEqual($result['metaDescription'], 'CrazySoft is an Australian professional online CMS company. We develop first-class websites, online apps for our clients and we also provide enterprise level marketing solutions.');

	}

}
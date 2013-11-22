<?php

require_once __DIR__.'/../BaseTestCase.php';

/**
 * 站点默认模块的单元测试用例
 */
class Site_IndexControllerTest extends BaseTestCase
{

    /**
     * 测试首页可以正常访问
     */
    public function testHomePageShouldBeAccessed()
    {
        $this->dispatch('/');
        $this->assertModule('site');
        $this->assertController('index');
        $this->assertAction('index');

        $response = $this->getResponse();
        $this->assertContains('Congratulations', $response->getBody());
    }

    /*

    public function testLoginPageShouldContainLoginForm()
    {
        $this->dispatch('/user');
        $this->assertAction('index');
        $this->assertQueryCount('form#loginForm', 1);
    }

    public function testValidLoginShouldGoToProfilePage()
    {
        $this->request->setMethod('POST')
            ->setPost(array(
                'username' => 'foobar',
                'password' => 'foobar'
            ));
        $this->dispatch('/user/login');
        $this->assertRedirectTo('/user/view');

        $this->resetRequest()
            ->resetResponse();

        $this->request->setMethod('GET')
            ->setPost(array());
        $this->dispatch('/user/view');
        $this->assertRoute('default');
        $this->assertModule('default');
        $this->assertController('user');
        $this->assertAction('view');
        $this->assertNotRedirect();
        $this->assertQuery('dl');
        $this->assertQueryContentContains('h2', 'User: foobar');
    }*/
}
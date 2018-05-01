<?php
//use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
//use Illuminate\Foundation\Testing\DatabaseTransactions;
use Minhbang\User\User;
use Tests\TestCase;

class CategoryManageTest extends TestCase
{
    use DatabaseMigrations;
    /**
     * @var array
     */
    protected $users = [];

    public function setUp()
    {
        parent::setUp();
        $this->users['user'] = factory(User::class)->create();
        $this->users['admin'] = factory(User::class, 'admin')->create();
        $this->users['super_admin'] = factory(User::class, 'super_admin')->create();
    }


    /**
     * Truy cập trang quản lý category
     */
    public function testAccessCategoryManagementPage()
    {
        // Yêu cầu đăng nhập khi truy cập
        $this->visit('/backend/category')
            ->seePageIs('/auth/login');

        // Không có quyền truy cập
        $this->actingAs($this->users['user'])->get('/backend/category')
            ->assertResponseStatus(403);

        // Truy cập thành công
        $this->actingAs($this->users['admin'])->get('/backend/category')
            ->assertResponseOk();

        // Truy cập bằng quyền Super Admin
        $this->actingAs($this->users['super_admin'])->visit('/backend/category')
            ->see(__('Manage Category'));
    }
}
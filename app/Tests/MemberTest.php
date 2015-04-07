<?php
namespace Tricolore\Tests;

use Tricolore\Security\Encoder\BCrypt;
use Carbon\Carbon;

class MemberTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $this->getDataSource()->buildQuery('create_table')
            ->name('members')
            ->columns([
                'id' => 'serial PRIMARY KEY',
                'username' => 'text',
                'password' => 'text',
                'group_id' => 'integer',
                'joined' => 'integer',
                'email' => 'text',
                'token' => 'text',
                'ip_address' => 'text'
            ])
            ->ifNotExists()
            ->execute();

        $this->getDataSource()->buildQuery('insert')
            ->into('members')
            ->values([
                'username' => 'Testing',
                'password' => BCrypt::hash('test_pass'),
                'group_id' => 1,
                'joined' => Carbon::now()->timestamp,
                'email' => 'testing@example.com',
                'token' => BCrypt::hash('test_token'),
                'ip_address' => '0.0.0.0'
            ])
            ->execute();
    }

    private function getDataSource()
    {
        return $this->getMockForAbstractClass('Tricolore\Services\ServiceLocator')
            ->get('datasource');
    }

    private function getMember()
    {
        return $this->getMockForAbstractClass('Tricolore\Services\ServiceLocator')
            ->get('member');  
    }

    private function getMemberLoad()
    {
        return $this->getMockForAbstractClass('Tricolore\Services\ServiceLocator')
            ->get('load_member');  
    }

    public function testMemberExistsByEmail()
    {
        $exists = $this->getMemberLoad()
            ->byEmail('testing@example.com')
            ->exists();

        $this->assertTrue($exists);    
    }

    public function testMemberExistsByEmailFail()
    {
        $exists = $this->getMemberLoad()
            ->byEmail('testingexample.com')
            ->exists();

        $this->assertFalse($exists);    
    }

    public function testMemberExistsById()
    {
        $exists = $this->getMemberLoad()
            ->byId(1)
            ->exists();

        $this->assertTrue($exists);    
    }

    public function testMemberExistsByIdFail()
    {
        $exists = $this->getMemberLoad()
            ->byId(99)
            ->exists();

        $this->assertFalse($exists);    
    }

    public function testMemberExistsByUsername()
    {
        $exists = $this->getMemberLoad()
            ->byUsername('Testing')
            ->exists();

        $this->assertTrue($exists);    
    }

    public function testMemberExistsByUsernameFail()
    {
        $exists = $this->getMemberLoad()
            ->byUsername('testing')
            ->exists();

        $this->assertFalse($exists);    
    }

    public function testMemberData()
    {
        $data = $this->getMemberLoad()
            ->byEmail('testing@example.com')
            ->container();

        $this->assertSame($data['id'], 1);
        $this->assertSame($data['username'], 'Testing');
        $this->assertSame($data['email'], 'testing@example.com');
    }

    public function testMemberDataNoResults()
    {
        $data = $this->getMemberLoad()
            ->byEmail('testingexample.com')
            ->container();

        $this->assertFalse($data);
    }

    public function testMemberValidate()
    {
        $load_member = $this->getMemberLoad()
            ->byEmail('testing@example.com');

        $password = 'test_pass';

        $status = $this->getMember()->validate($load_member, $password);

        $this->assertTrue($status);
    }

    public function testMemberValidateFail()
    {
        $load_member = $this->getMemberLoad()
            ->byEmail('testing@example.com');

        $password = 'testpass';

        $status = $this->getMember()->validate($load_member, $password);

        $this->assertSame($status, 'Password for this account is not valid.');
    }

    public function testMemberValidateFailNotFoundMember()
    {
        $load_member = $this->getMemberLoad()
            ->byEmail('test@example.com');

        $password = 'test_pass';

        $status = $this->getMember()->validate($load_member, $password);

        $this->assertSame($status, 'Account with this username or email not exists.');
    }

    protected function tearDown()
    {
        $this->getDataSource()->dropTable('members');
    }
}

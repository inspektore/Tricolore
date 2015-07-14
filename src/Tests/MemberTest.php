<?php
namespace Tricolore\Tests;

use Tricolore\Security\Encoder\BCrypt;
use Carbon\Carbon;
use Symfony\Component\Security\Core\Util\SecureRandom;

class MemberTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $generator = new SecureRandom();

        $this->getDataSource()->buildQuery('create_table')
            ->name('members')
            ->columns([
                'id' => 'serial PRIMARY KEY',
                'username' => 'text',
                'password' => 'text',
                'group_id' => 'integer',
                'role' => 'text',
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
                'role' => 'ROLE_ADMIN',
                'joined' => Carbon::now()->timestamp,
                'email' => 'testing@example.com',
                'token' => BCrypt::hash(bin2hex($generator->nextBytes(25))),
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

    private function getMemberFinder()
    {
        return $this->getMockForAbstractClass('Tricolore\Services\ServiceLocator')
            ->get('member.finder');  
    }

    public function testMemberExistsByEmail()
    {
        $exists = $this->getMemberFinder()
            ->byEmail('testing@example.com')
            ->exists();

        $this->assertTrue($exists);    
    }

    public function testMemberExistsByEmailFail()
    {
        $exists = $this->getMemberFinder()
            ->byEmail('testingexample.com')
            ->exists();

        $this->assertFalse($exists);    
    }

    public function testMemberExistsById()
    {
        $exists = $this->getMemberFinder()
            ->byId(1)
            ->exists();

        $this->assertTrue($exists);    
    }

    public function testMemberExistsByIdFail()
    {
        $exists = $this->getMemberFinder()
            ->byId(99)
            ->exists();

        $this->assertFalse($exists);    
    }

    public function testMemberExistsByUsername()
    {
        $exists = $this->getMemberFinder()
            ->byUsername('Testing')
            ->exists();

        $this->assertTrue($exists);    
    }

    public function testMemberExistsByUsernameFail()
    {
        $exists = $this->getMemberFinder()
            ->byUsername('testing')
            ->exists();

        $this->assertFalse($exists);    
    }

    public function testMemberData()
    {
        $data = $this->getMemberFinder()
            ->byEmail('testing@example.com')
            ->container();

        $this->assertSame($data['id'], 1);
        $this->assertSame($data['username'], 'Testing');
        $this->assertSame($data['email'], 'testing@example.com');
    }

    public function testMemberDataNoResults()
    {
        $data = $this->getMemberFinder()
            ->byEmail('testingexample.com')
            ->container();

        $this->assertFalse($data);
    }

    public function testMemberValidate()
    {
        $load_member = $this->getMemberFinder()
            ->byEmail('testing@example.com');

        $password = 'test_pass';

        $status = $this->getMember()->validate($load_member, $password);

        $this->assertTrue($status);
    }

    public function testMemberValidateFail()
    {
        $load_member = $this->getMemberFinder()
            ->byEmail('testing@example.com');

        $password = 'testpass';

        $status = $this->getMember()->validate($load_member, $password);

        $this->assertSame($status, 'Password for this account is not valid.');
    }

    public function testMemberValidateFailNotFoundMember()
    {
        $load_member = $this->getMemberFinder()
            ->byEmail('test@example.com');

        $password = 'test_pass';

        $status = $this->getMember()->validate($load_member, $password);

        $this->assertSame($status, 'Account with this username or email not exists.');
    }

    public function testLoadMemberFindByStrategyEmail()
    {
        $email = 'testing@example.com';
        $load_member = $this->getMemberFinder()->findByStrategy($email);

        $this->assertTrue($load_member->exists());
    }

    public function testLoadMemberFindByStrategyUsername()
    {
        $username = 'Testing';
        $load_member = $this->getMemberFinder()->findByStrategy($username);

        $this->assertTrue($load_member->exists());
    }

    public function testLoadMemberFindByStrategyEmailFail()
    {
        $email = 'test@example.com';
        $load_member = $this->getMemberFinder()->findByStrategy($email);

        $this->assertFalse($load_member->exists());
    }

    public function testLoadMemberFindByStrategyUsernameFail()
    {
        $username = 'Test';
        $load_member = $this->getMemberFinder()->findByStrategy($username);

        $this->assertFalse($load_member->exists());
    }

    public function testMemberCreate()
    {

    }

    /**
     * @expectedException Tricolore\Exception\LogicException
     * @expectedExceptionMessage The required strategy byId(), byEmail() or byUsername() is missing.
     */
    public function testLoadMemberMissingStrategy()
    {
        $this->getMemberFinder()->container();
    }

    protected function tearDown()
    {
        $this->getDataSource()->dropTable('members');
    }
}

<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Services\User\Manager\UserManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;


class AppFixtures extends Fixture
{

    /**
     * @var UserManager
     */
    private $userManager;

    public function __construct(UserManager $userManager)
    {

        $this->userManager = $userManager;
    }

    /**
     * Load data fixtures with the passed EntityManager.
     */
    public function load(ObjectManager $manager): void
    {
        $this->loadUsers();

    }

    public function loadUsers(): void
    {
        $userAdmin = new User();
        $userAdmin->setNom('admin');
        $userAdmin->setPrenom('admin');
        $userAdmin->setPassword('admin123');
        $userAdmin->setEmail('admin@admin.fr');
        $userAdmin->setRoles(['ROLE_ADMIN']);

        /**
         * UserManager is using to encode the password easily
         */
        $this->userManager->create($userAdmin);

        $this->addReference('admin-user', $userAdmin);
    }









}

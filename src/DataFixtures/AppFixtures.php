<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Service;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        // Create 3 categories
        $categories = [];
        for ($i = 0; $i < 3; $i++) {
            $category = new Category();
            $category->setName($faker->word);
            $manager->persist($category);
            $categories[] = $category;
        }

        // Create 100 services
        for ($i = 0; $i < 100; $i++) {
            $service = new Service();
            $service->setTitle($faker->sentence(3));
            $service->setDescription($faker->paragraph);
            $service->setCity($faker->city);
            $service->setAdress($faker->address);
            $service->setPostcode($faker->postcode);
            $service->setDuration($faker->numberBetween(30, 120));

            // Assign random categories to the service
            $randomCategories = $faker->randomElements($categories, $faker->numberBetween(1, 3));
            foreach ($randomCategories as $category) {
                $service->addCategory($category);
            }

            $manager->persist($service);
        }

        $manager->flush();
    }
}
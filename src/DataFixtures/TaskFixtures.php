<?php

namespace App\DataFixtures;

use App\Entity\Task;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class TaskFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('ru_RU');
        $statuses = ['новая', 'в процессе', 'завершена', 'отложена'];

        for ($i = 0; $i < 20; $i++) {
            $task = new Task();
            $task->setName($faker->sentence(3));
            $task->setDescription($faker->paragraph(2));
            $task->setStatus($faker->randomElement($statuses));

            $manager->persist($task);
        }

        $manager->flush();
    }
}

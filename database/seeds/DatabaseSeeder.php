<?php

use App\Conclusion;
use App\ConclusionResult;
use App\Contact;
use App\DrivingCategory;
use App\DrivingSchool;
use App\LearningPlace;
use App\Program;
use App\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        if (! DrivingCategory::count()) {
            DrivingCategory::create([
                'name' => 'RND',
                'short_description' => 'Category placeholder',
            ]);
        }

        // Создать пользователей
        factory(User::class, 10)->create()->each(function (User $user) {
            // На каждого пользователя создать 1-2 школы
            factory(DrivingSchool::class, random_int(1, 2))
                ->create(['author_id' => $user->id])
                ->each(function (DrivingSchool $drivingSchool) {
                    // Добавить автошколам водительские категории
                    $drivingCategories = DrivingCategory::inRandomOrder()->limit(random_int(1, 5))->get();
                    $drivingSchool->driving_categories()->attach($drivingCategories);

                    // Некоторым школам добавить заключение
                    $conclusion = null;
                    if (random_int(1, 2) === 1) {
                        $conclusion = factory(Conclusion::class)->create(['driving_school_id' => $drivingSchool->id]);

                        // Добавить заключению часть водительских категорий автошколы
                        $conclusion->driving_categories()->attach(
                            // От 0 до всех категорий автошколы
                            $drivingCategories->random(random_int(0, $drivingCategories->count()))
                        );
                    }

                    // На каждую школу создать одно или несколько учебных мест
                    factory(LearningPlace::class, random_int(1, 3))
                        ->create(['driving_school_id' => $drivingSchool->id])
                        ->each(function (LearningPlace $learningPlace) use ($conclusion) {
                            // Если у школы есть заключение создать одно или несколько результатов заключений
                            if ($conclusion) {
                                $p = [1, 1, 1, 2];
                                factory(ConclusionResult::class, $p[array_rand($p)])->create([
                                    'learning_place_id' => $learningPlace->id,
                                    'conclusion_id' => $conclusion->id,
                                ]);
                            }
                        });

                    // Некоторым автошколам добавить программы
                    factory(Program::class, random_int(0, 4))
                        ->create(['driving_school_id' => $drivingSchool->id])
                        ->each(function (Program $program) use ($drivingCategories) {
                            // Добавить в программы учебные места
                            $program->learning_places()->attach($program->driving_school->learning_places->random());

                            // Добавить программе часть водительских категорий автошколы
                            $program->driving_categories()->attach(
                                // От 0 до всех категорий автошколы
                                $drivingCategories->random(random_int(0, $drivingCategories->count()))
                            );
                        });

                    // Каждой автошколе добавить контакты
                    $contacts = factory(Contact::class, random_int(1, 5))->make();
                    $drivingSchool->contacts()->saveMany($contacts);
                });
        });
    }
}

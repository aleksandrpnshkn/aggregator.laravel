<?php

namespace Tests\Feature\Controllers;

use App\Contact as ContactModel;
use App\DrivingSchool;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class ContactControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testInvalidContacts()
    {
        $author = factory(User::class)->create();
        $drivingSchool = factory(DrivingSchool::class)->create(['author_id' => $author->id]);

        Permission::create(['name' => 'edit driving school']);
        Permission::create(['name' => 'edit driving schools']);

        $badData = [
            [
                'id' => null,
                'contact_type' => ContactModel::TYPE_EMAIL,
                'value' => 'wrong@em  ail',
            ],
            [
                'id' => null,
                'contact_type' => ContactModel::TYPE_PHONE,
                'value' => '123 123 123 12 312 3123 12 31231 23 1123 123',
            ],
            [
                'id' => null,
                'contact_type' => ContactModel::TYPE_TELEGRAM,
                'value' => '@wr ong',
            ],
            [
                'id' => null,
                'contact_type' => ContactModel::TYPE_VIBER,
                'value' => '(434)1231',
            ],
            [
                'id' => null,
                'contact_type' => ContactModel::TYPE_URL,
                'value' => 'schema::////wrong',
            ],
        ];

        foreach ($badData as $data) {
            $this->actingAs($author)
                ->postJson(route('updateContact', $drivingSchool->slug), $data)
                ->assertStatus(422)
                ->assertJsonValidationErrors('value');
        }
    }

    public function testValidContacts()
    {
        $author = factory(User::class)->create();
        $drivingSchool = factory(DrivingSchool::class)->create(['author_id' => $author->id]);

        Permission::create(['name' => 'edit driving school']);
        Permission::create(['name' => 'edit driving schools']);

        $badData = [
            [
                'id' => null,
                'contact_type' => ContactModel::TYPE_EMAIL,
                'value' => 'valid@example.com',
            ],
            [
                'id' => null,
                'contact_type' => ContactModel::TYPE_PHONE,
                'value' => '8 (800) 123-45-67',
            ],
            [
                'id' => null,
                'contact_type' => ContactModel::TYPE_TELEGRAM,
                'value' => '@username',
            ],
            [
                'id' => null,
                'contact_type' => ContactModel::TYPE_VIBER,
                'value' => '+78001234567',
            ],
            [
                'id' => null,
                'contact_type' => ContactModel::TYPE_URL,
                'value' => 'https://example.com',
            ],
        ];

        foreach ($badData as $data) {
            $this->actingAs($author)
                ->postJson(route('updateContact', $drivingSchool->slug), $data)
                ->assertStatus(200);
        }
    }
}

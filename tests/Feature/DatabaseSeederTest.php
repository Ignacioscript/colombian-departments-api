<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DatabaseSeederTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Indicates if the database should be seeded before each test.
     *
     * @var bool
     */
    protected $seed = true;

    /**
     * A basic test to ensure the database is seeded.
     *
     * @return void
     */
    public function test_the_database_is_seeded_correctly(): void
    {
        // Assert that the 'departments' table has exactly 33 records.
        $this->assertDatabaseCount('departments', 33);

        $this->assertDatabaseHas('departments',     [
            'name' => 'Bogotá, D.C.',
            'slug' => 'bogota-dc',
            'is_capital' => true,
            'extension' => 1775,
            'population' => 7412566,
            'region' => 'Andina',
            'capital' => 'Bogotá',
            'code' => '11',
        ]);

        $this->assertDatabaseMissing('departments', [
            'name' => 'New york'
        ]);

        // Assert that the 'cities' table contains a record
        // for the city of 'Bucaramanga'.
        $this->assertDatabaseHas('cities', [
            'department_id' => 4,
            'name' => 'Barranquilla',
            'is_capital' => true,
            'population' => 1206319
        ]);

        // Assert that a non-existent city is, in fact, missing.
        $this->assertDatabaseMissing('cities', [
            'name' => 'Gotham City'
        ]);


    }
}

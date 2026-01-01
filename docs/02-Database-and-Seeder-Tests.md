# Lesson 2: Testing Your Database Seeders

Welcome back! Now that we understand the basics, let's write our first real test. Our goal is to verify that our database seeders are populating the database with the correct data. If the seeders fail, nothing else in our app will work, so it's the perfect place to start.

We will create a **Feature Test** for this, because we need the full Laravel application (including the database) to be active.

### Step 1: Create the Test File

Open your terminal and run the following Artisan command:

```bash
php artisan make:test DatabaseSeederTest
```

This will create a new file for us at `tests/Feature/DatabaseSeederTest.php`. Go ahead and open it.

### Step 2: Set Up the Test Class

Inside the `DatabaseSeederTest.php` file, we need to tell Laravel to use our in-memory database and run our migrations and seeders before each test.

We'll use a special trait called `RefreshDatabase`. This trait does three amazing things for us automatically before any test in this file is run:
1.  It sets up the in-memory database.
2.  It runs all of our migrations (`php artisan migrate`).
3.  It runs all of our seeders (`php artisan db:seed`).

Your test file should look like this:

```php
<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase; // <-- Import the trait
use Tests\TestCase;

class DatabaseSeederTest extends TestCase
{
    use RefreshDatabase; // <-- Use the trait

    /**
     * A basic test to ensure the database is seeded.
     *
     * @return void
     */
    public function test_the_database_is_seeded_correctly(): void
    {
        // Our test logic will go here!
    }
}
```

### Step 3: Writing the Test Logic

Now for the fun part! Let's write the logic inside the `test_the_database_is_seeded_correctly` method. We want to verify two things:
1.  Are there exactly 33 departments in our `departments` table?
2.  Is the city of 'Bucaramanga' correctly registered in the `cities` table?

We can use the `assertDatabaseCount` and `assertDatabaseHas` methods, which are incredibly readable and provided by Laravel.

```php
public function test_the_database_is_seeded_correctly(): void
{
    // 1. Assert that the 'departments' table has exactly 33 records.
    $this->assertDatabaseCount('departments', 33);

    // 2. Assert that the 'cities' table contains a record
    //    for the city of 'Bucaramanga'.
    $this->assertDatabaseHas('cities', [
        'name' => 'Bucaramanga'
    ]);

    // 3. (Bonus) Assert that a non-existent city is, in fact, missing.
    $this->assertDatabaseMissing('cities', [
        'name' => 'Gotham City'
    ]);
}
```

### How It Works and Why

*   `$this->assertDatabaseCount('table_name', count)`: This is a direct and clear assertion. It queries the database and checks if the number of rows in the specified table matches the count you provide. It's the perfect way to verify your seeder ran completely.

*   `$this->assertDatabaseHas('table_name', ['column' => 'value'])`: This method checks if **at least one row** exists in the table that matches the criteria in the array. It's fantastic for confirming that specific, critical data points were seeded correctly.

*   `$this->assertDatabaseMissing(...)`: The opposite of `assertDatabaseHas`. It confirms that no records match the given criteria. This is useful for preventing regressions and ensuring old or incorrect data doesn't accidentally creep back into your seeders.

### Step 4: Run the Test!

Go back to your terminal and run the test command:

```bash
php artisan test --filter DatabaseSeederTest
```

The `--filter` option tells PHPUnit to only run tests from this specific file.

You should see a beautiful, passing test suite! You have now built a safety net that guarantees your database is always populated correctly.

In our next lesson, we'll move on to testing the relationships between our Models. Keep up the great work!

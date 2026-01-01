# Lesson 3: Testing Your Model Relationships

Hello again! In our last lesson, we confirmed our data exists in the database. Now, let's make sure our Eloquent models can interact with that data correctly. We need to test the **relationships** between our models.

For this lesson, we assume you have two models:
- `app/Models/Department.php`
- `app/Models/City.php`

And that you have defined the relationships:
- A `Department` **has many** `City` records (`hasMany`).
- A `City` **belongs to** a `Department` (`belongsTo`).

### Step 1: Create the Test File

Let's create a new test file specifically for our `Department` model.

```bash
php artisan make:test Models/DepartmentTest --unit
```

Notice the `--unit` flag. We're creating a **Unit Test** because we want to test a small, isolated piece of our application—the model itself. This will create the file at `tests/Unit/Models/DepartmentTest.php`.

### Step 2: Set Up the Test Class

Just like before, we need our database to be ready. We'll use the `RefreshDatabase` trait again. Even though it's a "unit" test, testing a relationship requires database interaction.

```php
<?php

namespace Tests\Unit\Models;

use App\Models\Department; // <-- Import the models
use App\Models\City;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DepartmentTest extends TestCase
{
    use RefreshDatabase;

    // Test methods will go here
}
```

### Step 3: Writing the Relationship Tests

Let's add our test methods. We want to confirm two things:
1.  Can we get all the cities that belong to a specific department?
2.  Can we find the parent department from a specific city?

#### Test 1: A Department Has Many Cities

```php
public function test_a_department_has_many_cities(): void
{
    // 1. SETUP: Find a specific department and one of its cities from the database.
    // We use firstWhere to get a predictable result.
    $department = Department::where('name', 'Santander')->first();
    $city = City::where('name', 'Bucaramanga')->first();

    // 2. ACTION & ASSERTION: Check if the collection of cities
    //    related to the department actually contains the city we expect.
    $this->assertTrue($department->cities->contains($city));

    // 3. ASSERTION: Check the inverse. Does the city's department match?
    $this->assertEquals($department->id, $city->department->id);
}
```

**How It Works and Why:**

*   **Setup (`where(...)`):** We're not just grabbing any department; we're grabbing a *specific*, known one. This makes our test predictable and reliable.
*   **`$department->cities`:** This is the magic! We are calling the `cities()` relationship as a dynamic property. Eloquent automatically queries the database to find all cities where `department_id` matches our `$department->id`. The result is a `Collection` of `City` models.
*   **`->contains($city)`:** The `contains()` method is a helper on Laravel Collections. It checks if the given item exists in the collection. It's a highly readable way to confirm the relationship is working.
*   **`$city->department`:** We also test the inverse relationship. This ensures that when we have a `City` model, we can correctly fetch its parent `Department`.

#### Test 2: A Department has a Capital City

Let's imagine you have a special relationship on your `Department` model to get its capital directly:

```php
// In your App\Models\Department.php model
public function capital()
{
    return $this->hasOne(City::class)->where('is_capital', true);
}
```

We can test this specific relationship.

```php
public function test_a_department_has_one_capital_city(): void
{
    // 1. SETUP: Get a department.
    $department = Department::where('name', 'Atlántico')->first();

    // 2. ACTION & ASSERTION: Check if the capital's name is correct.
    $this->assertEquals('Barranquilla', $department->capital->name);
}
```

### Step 4: Run Your Tests

Now, run your new test file from the terminal:

```bash
php artisan test --filter DepartmentTest
```

With these tests in place, you can refactor your model relationships with confidence, knowing that this safety net will immediately tell you if you've broken anything.

Next up: the grand finale! We'll test our API endpoints.

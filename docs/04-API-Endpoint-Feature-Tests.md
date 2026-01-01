# Lesson 4: Testing Your API Endpoints

Welcome to the final and most crucial lesson for our API project! Here, we test our application from the outside, just like a real user would. We will make actual HTTP requests to our API endpoints and inspect the responses to ensure they are correct.

This is called **Feature Testing**, and it gives you the highest level of confidence that your application works from end to end.

For this lesson, let's assume you have defined the following route in your `routes/api.php` file:

```php
// in routes/api.php
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/departments', function () {
    return Department::all();
});
```

### Step 1: Create the Test File

Let's create a new feature test file for our department-related API endpoints.

```bash
php artisan make:test Features/DepartmentsApiTest
```

This command will create the file at `tests/Feature/DepartmentsApiTest.php`.

### Step 2: Set Up the Test Class

As always, we start by using the `RefreshDatabase` trait so we have a clean, seeded database for every test.

```php
<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DepartmentsApiTest extends TestCase
{
    use RefreshDatabase;

    // Our API test methods will go here
}
```

### Step 3: Writing the API Tests

Let's write a test to verify the behavior of the `GET /api/departments` endpoint.

#### Test 1: Can We Get All Departments?

```php
public function test_it_returns_all_departments_successfully(): void
{
    // 1. ACTION: Make a GET request to the API endpoint.
    $response = $this->get('/api/departments');

    // 2. ASSERTIONS:
    // a) Assert that the HTTP response status is 200 (OK).
    $response->assertStatus(200);

    // b) Assert that the response contains a JSON array with exactly 33 items.
    $response->assertJsonCount(33);

    // c) Assert that the JSON response has a specific structure.
    //    This checks if the first department in the response has the expected keys.
    $response->assertJsonStructure([
        '*' => [ // The '*' means "each item in the collection"
            'id',
            'name',
            'slug',
            'capital',
            'population',
            'created_at',
            'updated_at'
        ]
    ]);
}
```

**How It Works and Why:**

*   `$this->get('/api/departments')`: This is the core of the test. Laravel makes an actual `GET` request to your application. The `$response` object it returns is incredibly powerful and contains the status, headers, and JSON body of the response.

*   `$response->assertStatus(200)`: This is the most basic and important assertion. It checks if the request was successful. If you get a 404 (Not Found) or 500 (Server Error), this test will fail immediately.

*   `$response->assertJsonCount(33)`: This checks the `count()` of the top-level JSON array in the response. It's a direct way to verify that all departments were returned.

*   `$response->assertJsonStructure([...])`: This is a powerful assertion that verifies the *shape* of your JSON response. It doesn't care about the *values*, only that the specified *keys* are present. The `*` is a wildcard that means "check this structure for every element in the main array." This is crucial for ensuring you don't accidentally remove a field from your API response in the future.

#### Test 2: Can We Get a Single Department?

Let's imagine you have another route: `GET /api/departments/{id}`.

```php
public function test_it_returns_a_single_department_successfully(): void
{
    // ACTION
    $response = $this->get('/api/departments/2'); // Request Antioquia (ID 2)

    // ASSERTIONS
    $response->assertStatus(200);

    // Assert that the returned JSON contains the correct name for ID 2.
    $response->assertJson([
        'name' => 'Antioquia'
    ]);
}
```

**How It Works and Why:**

*   `$this->get('/api/departments/2')`: We make a request for a specific, known resource.
*   `$response->assertJson(['name' => 'Antioquia'])`: Unlike `assertJsonStructure`, `assertJson` checks the **values** in the response. It verifies that the JSON response contains the given key-value pair. It's perfect for confirming you got the *exact* record you asked for.

### Step 4: Run Your Tests!

```bash
php artisan test --filter DepartmentsApiTest
```

You now have a robust suite of feature tests that validate the core functionality of your API. Whenever you change your code, you can run these tests to be instantly confident that you haven't broken anything for the users of your API.

Congratulations, you are now a Laravel testing pro!

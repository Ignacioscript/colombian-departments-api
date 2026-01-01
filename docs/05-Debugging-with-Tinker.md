# Lesson 5: Exploring Your Application with Tinker

Welcome back, student! Today, we're going to learn about one of my favorite Laravel tools: **Tinker**.

Think of `php artisan tinker` as a direct, interactive command line into your entire Laravel application. It's a REPL (Read-Eval-Print Loop) that has your whole app booted up and ready to go. You can run any PHP code you want, call your models, dispatch jobs, and interact with your database—all without needing to create a route or a controller.

It is, without a doubt, the ultimate debugging and exploration tool.

### Getting Started

To begin your Tinker session, simply run this command in your terminal:

```bash
php artisan tinker
```

You'll be greeted with a `psy>` prompt. You are now "inside" your application.

### Playing with Your Data (Eloquent)

This is the most common use case. You can query and interact with your models directly.

#### Find a record
```bash
// Find the department with ID = 2
$dept = App\Models\Department::find(2);

// Find a city by its name
$city = App\Models\City::where('name', 'Cali')->first();
```

#### Access attributes and relationships
Once you have a model instance, you can inspect it.
```bash
// See the attributes of the department
$dept->name;
// => "Antioquia"

$dept->capital;
// => "Medellín"

// Access its related cities!
$dept->cities;
// => Illuminate\Database\Eloquent\Collection {#...}

// You can even chain commands, like getting the name of the first city
$dept->cities->first()->name;
// => "Medellín"
```

#### Test a piece of logic
Imagine you want to see what happens when you create a new city. You can do it right here!
```bash
App\Models\City::create([
    'department_id' => 28, // Santander
    'name' => 'Gotham City',
    'is_capital' => false,
    'population' => 1000000
]);
```
Tinker will show you the newly created model. If there's an error (like a missing field), it will tell you immediately. This is much faster than writing the logic in a controller and testing it in the browser.

### Peeking at the Database Schema

Sometimes you don't need the data; you just need to know if the *structure* of the database is correct. For this, we use the `Schema` facade.

#### Does a table exist?
This is great for debugging migrations.
```bash
use Illuminate\Support\Facades\Schema;

Schema::hasTable('departments');
// => true

Schema::hasTable('users');
// => false (assuming you don't have a users table)
```

#### Does a column exist in a table?
Did you remember to add the `population` column? Let's check.
```bash
Schema::hasColumn('departments', 'population');
// => true

Schema::hasColumn('departments', 'country');
// => false
```
If a test is failing because of a "column not found" error, this is the quickest way to verify your database schema without leaving the command line.

### Using the DB Facade for Raw Queries

While Eloquent is amazing, sometimes you want to run a slightly more direct query. The `DB` facade is available too.

```bash
use Illuminate\Support\Facades\DB;

// Get all records from the cities table
DB::table('cities')->get();

// Get all cities in department #31 (Valle del Cauca)
DB::table('cities')->where('department_id', 31)->pluck('name');
// => Illuminate\Support\Collection {#...,
//      all: [
//        "Cali",
//        "Buenaventura",
//        "Palmira",
//        "Tuluá",
//        "Yumbo",
//      ],
//    }
```

### Tinker as Your Scratchpad

Tinker is the perfect place to test out a tricky piece of code before you write it "for real." For example, if you need to transform some data with collection methods, do it in Tinker first!

```bash
// Let's get all departments and create a simple string for each.
$depts = App\Models\Department::all();

$depts->map(function ($dept) {
    return "The capital of " . $dept->name . " is " . $dept->capital . ".";
});
```
Tinker will immediately show you the resulting collection. You can perfect your logic here, and once it's working, copy-paste it into your controller or service class.

To exit the Tinker session at any time, just type `exit` or press `Ctrl+C`.

Embrace Tinker, and it will become one of your most trusted assistants in your development journey!

<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Category;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function it_can_display_category_index_page()
    {
        // Arrange
        $categories = Category::factory(3)->create();

        // Act
        $response = $this->get(route('categories.index'));

        // Assert
        $response->assertStatus(200)
            ->assertViewIs('categories.index')
            ->assertViewHas('categories')
            ->assertSee($categories[0]->name)
            ->assertSee($categories[1]->name)
            ->assertSee($categories[2]->name);
    }

    /** @test */
    public function it_can_display_create_category_form()
    {
        // Act
        $response = $this->get(route('categories.create'));

        // Assert
        $response->assertStatus(200)
            ->assertViewIs('categories.create');
    }

    /** @test */
    public function it_can_store_a_new_category()
    {
        // Arrange
        $categoryData = Category::factory()->make()->toArray();

        // Act
        $response = $this->post(route('categories.store'), $categoryData);

        // Assert
        $response->assertRedirect(route('categories.index'))
            ->assertSessionHas('success', 'Category has been created!');

        $this->assertDatabaseHas('categories', $categoryData);
    }

    /** @test */
    public function it_can_display_edit_category_form()
    {
        // Arrange
        $category = Category::factory()->create();

        // Act
        $response = $this->get(route('categories.edit', $category));

        // Assert
        $response->assertStatus(200)
            ->assertViewIs('categories.edit')
            ->assertViewHas('category', $category);
    }

    /** @test */
    public function it_can_update_category_information()
    {
        // Arrange
        $category = Category::factory()->create();
        $updatedCategoryData = Category::factory()->make()->toArray();

        // Act
        $response = $this->put(route('categories.update', $category), $updatedCategoryData);

        // Assert
        $response->assertRedirect(route('categories.index'))
            ->assertSessionHas('success', 'Category has been updated!');

        $this->assertDatabaseHas('categories', $updatedCategoryData);
        $this->assertDatabaseMissing('categories', $category->toArray());
    }

    /** @test */
    public function it_can_delete_category()
    {
        // Arrange
        $category = Category::factory()->create();

        // Act
        $response = $this->delete(route('categories.destroy', $category));

        // Assert
        $response->assertRedirect()
            ->assertSessionHas('success', 'Category has been deleted!');

        $this->assertDatabaseMissing('categories', $category->toArray());
    }
}

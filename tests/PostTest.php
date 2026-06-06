<?php

declare(strict_types=1);

use App\Models\Post;
use App\Models\User;

final class PostTest extends ModelTestCase
{
    public function testSearchMatchesAuthorAndExcludesDrafts(): void
    {
        $users = new User($this->pdo);
        $authorId = $users->create('searchable_author', 'author@example.com', 'Password123!');
        $posts = new Post($this->pdo);

        $posts->create([
            'title' => 'A published article',
            'content' => 'This is sufficiently long published article content.',
            'excerpt' => '',
            'category_id' => null,
            'user_id' => $authorId,
            'status' => 'published',
        ]);
        $posts->create([
            'title' => 'A private draft',
            'content' => 'This is sufficiently long private draft content.',
            'excerpt' => '',
            'category_id' => null,
            'user_id' => $authorId,
            'status' => 'draft',
        ]);

        $result = $posts->paginatePublished('searchable_author', 1);

        self::assertSame(1, $result['total']);
        self::assertCount(1, $result['items']);
        self::assertSame('A published article', $result['items'][0]['title']);
    }

    public function testSearchMatchesPublishedTitleAndContent(): void
    {
        $userId = (new User($this->pdo))->create('writer_two', 'writer2@example.com', 'Password123!');
        $posts = new Post($this->pdo);

        $posts->create([
            'title' => 'Unique Architecture Guide',
            'content' => 'This published body contains a special security phrase.',
            'excerpt' => '',
            'category_id' => null,
            'user_id' => $userId,
            'status' => 'published',
        ]);

        self::assertSame(1, $posts->paginatePublished('Architecture', 1)['total']);
        self::assertSame(1, $posts->paginatePublished('security phrase', 1)['total']);
    }

    public function testPaginationReturnsExactlyTenPostsPerPage(): void
    {
        $userId = (new User($this->pdo))->create('writer', 'writer@example.com', 'Password123!');
        $posts = new Post($this->pdo);

        for ($number = 1; $number <= 11; $number++) {
            $posts->create([
                'title' => 'Published Post ' . $number,
                'content' => 'This is sufficiently long content for post number ' . $number . '.',
                'excerpt' => '',
                'category_id' => null,
                'user_id' => $userId,
                'status' => 'published',
            ]);
        }

        $firstPage = $posts->paginatePublished('', 1);
        $secondPage = $posts->paginatePublished('', 2);

        self::assertCount(10, $firstPage['items']);
        self::assertCount(1, $secondPage['items']);
        self::assertSame(2, $firstPage['last_page']);
    }
}

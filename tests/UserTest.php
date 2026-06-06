<?php

declare(strict_types=1);

use App\Models\User;

final class UserTest extends ModelTestCase
{
    public function testCreateStoresAHashAndFindsTheUserByEmail(): void
    {
        $model = new User($this->pdo);
        $id = $model->create('alice', 'Alice@Example.com', 'StrongPass123!');
        $user = $model->findByEmail('alice@example.com');

        self::assertSame($id, (int) $user['id']);
        self::assertSame('alice@example.com', $user['email']);
        self::assertNotSame('StrongPass123!', $user['password']);
        self::assertTrue(password_verify('StrongPass123!', $user['password']));
    }

    public function testUpdatePasswordReplacesTheStoredHash(): void
    {
        $model = new User($this->pdo);
        $id = $model->create('bob', 'bob@example.com', 'OldPassword!');
        $oldHash = $model->findById($id)['password'];

        self::assertTrue($model->updatePassword($id, 'NewPassword!'));

        $newHash = $model->findById($id)['password'];
        self::assertNotSame($oldHash, $newHash);
        self::assertTrue(password_verify('NewPassword!', $newHash));
    }
}

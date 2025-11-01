<?php

namespace app\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * Đây là lớp Model cho bảng "NhanVien", được sử dụng làm Identity Model.
 *
 * @property int $ID_NHANVIEN
 * @property string $MaNV
 * @property string $TenNV
 * @property string $VaiTro
 * @property string $username
 * @property string $password_hash
 * @property string $auth_key
 */
class User extends NhanVien implements IdentityInterface
{
    // Cần thêm các thuộc tính khác của NhanVien nếu muốn sử dụng chúng
    public $ID_NHANVIEN;
    public $MaNV;
    public $TenNV;
    public $VaiTro;
    public $username;
    public $password_hash;
    public $auth_key;

    // --- IdentityInterface Methods ---

    /**
     * Finds an identity by the given ID.
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface|null the identity object that matches the given ID.
     */
    public static function findIdentity($id)
    {
        // Sử dụng Primary Key của bảng NhanVien
        return static::findOne($id);
    }

    /**
     * Finds an identity by the given token.
     * @param string $token the token to be looked for
     * @param null $type
     * @return IdentityInterface|null the identity object that matches the given token.
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        // Không cần thiết cho ứng dụng web cơ bản, có thể để trống hoặc throw exception
        return null;
    }

    /**
     * Returns an ID that can uniquely identify user identity.
     * @return string|int an ID that can uniquely identify user identity.
     */
    public function getId()
    {
        return $this->ID_NHANVIEN;
    }

    /**
     * Returns the key used to check the user identity.
     * @return string a key used to check the user identity.
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * Validates the given auth key.
     * @param string $authKey the given auth key
     * @return bool whether the given auth key is valid.
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    // --- Custom Methods ---

    /**
     * Finds user by username.
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['Username' => $username]);
    }

    /**
     * Validates password.
     * @param string $password password to validate
     * @return bool whether the password is valid
     */
    public function validatePassword($password)
    {
        // So sánh password nhập vào với password_hash trong CSDL
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    // --- Overriding Active Record ---

    /**
     * @return string the name of the table associated with this ActiveRecord class.
     */
    public static function tableName()
    {
        return '{{%NhanVien}}';
    }
}